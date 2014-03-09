<?
/**
 * cron
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	cron
 */


$CMSCRONTAB[] = array(
	"name" => "cmscore_optimize_tables",
	"info" => "Optymalizacja tabel systemowych",
);

/**
 * @category	crontab
 * @package	core
 * @version	5.0.0
*/
function cmscore_optimize_tables() {

	content_cache_clear();
	$SQL_QUERY = "OPTIMIZE TABLE ".DB_TABLEPREFIX."_content_cache";
	try { $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("sql()",$SQL_QUERY,$e); }

	$SQL_QUERY = "OPTIMIZE TABLE ".DB_TABLEPREFIX."_core_changed";
	try { $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("sql()",$SQL_QUERY,$e); }

	$SQL_QUERY = "OPTIMIZE TABLE ".DB_TABLEPREFIX."_session";
	try { $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("sql()",$SQL_QUERY,$e); }

	return array(1,"");
}

$CMSCRONTAB[] = array(
	"name" => "cmscore_coretask_process",
	"info" => "wykonywanie procesów z kolejki",
);

/**
 * @category	download
 * @package		core
 * @version		5.0.0
*/
function cmscore_coretask_process() {

	$max_execution_time = 45;
	$max_retries = 50;

	$start_time = microtime(true);

	for($a=1; $a<=$max_retries; $a++) {
		if($result = core_task_fetch_waiting( $limit=1 ) ) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)){

				core_task_lock( $row["core_task__id"] );

				$current_time = microtime(true);

				if( ($current_time - $start_time) > 60) {
					// finish if process takes longer than a minute
					return array(1,"");
				}

				$start = microtime(true);

				$_params = json_decode($row["core_task__params"], true);

				$str  = " \$return = ".$row["core_task__function"]."(";
				$str2="";
				foreach($_params AS $k=>$v){
					$str2 .= $str2 ? "," : "";
					$str2 .= " \$$k=\"$v\"";
				}
				$str .= $str2;
				$str .= " );\n";
				eval ( $str );

				$core_task__execution_time = microtime(true) - $start;
			#	echo "totaltime: ".(microtime(true) - $start_time).", ";
				echo "execution: $core_task__execution_time \n";


				core_task_result( $row["core_task__id"], $return, $core_task__execution_time );
			}
		}
		if( (microtime(true) - $start_time) >= $max_execution_time)
			return array(1,"");
	}
	return array(1,"");
}

$CMSCRONTAB[] = array(
        "name" => "cmscore_cron_systemupdate",
        "info" => __("core", "Aktualizacja systemu"),
);

/**
 * @category	download
 * @package		core
 * @version		5.0.0
*/
function cmscore_cron_systemupdate() {

	$update_url_version = "http://cms.polewiak.pl/dist/version_history.txt";
	$update_url_files = "http://cms.polewiak.pl/dist/cms_update_%n.tgz";

	echo "Current version: ".$SOFTWARE_INFORMATION["version"]."\n";
	echo "-- check available updates\n";
	// get update info
	$updates_info = file($update_url_version);
	for($i=0; $i<sizeof($updates_info); $i++) {
		$new_version = $version;
		list($version) = explode("\t", trim($updates_info[$i]));
		if ($version == $SOFTWARE_INFORMATION["version"]) {
			next;
			break;
		}
	}

	if (!$new_version) {
        	return array(1,"SYSTEM IS UP TO DATE");
	}
	echo "Update version: $new_version \n";

	$cmsupdate = "/tmp/cmsupdate-".time();
	mkdir( $cmsupdate );
	$cmsupdatedir = $cmsupdate."/".$new_version;
	mkdir( $cmsupdatedir );
	if (! is_dir($cmsupdatedir)) {
		return array(0, "Directory creation Error ($cmsupdatedir)");
	}

	if( ! copy( preg_replace("/(%n)/", trim($new_version), $update_url_files), $cmsupdatedir."/cms_update.tgz") ) {
		return array(0, "Error Copying update from server\n");
	}

	exec("which tar", $tar);
	exec($tar[0]." xzf ".$cmsupdatedir."/cms_update.tgz -C ".$cmsupdate);

	if (! is_file($cmsupdatedir."/cms_update.php")) {
		return array(0, "Update info not exists\n");
	}

	require $cmsupdatedir."/cms_update.php";

	if (is_array($update_files)){
		foreach($update_files AS $trgfile=>$info){

			$trgfilepath = explode("/", $trgfile);
			$checkdir = $ROOT_DIR;
			for($i=0;$i<sizeof($trgfilepath)-1;$i++) {
				$checkdir .= "/".$trgfilepath[$i];
				if (!is_dir($checkdir)){
					echo "Make Dir: $checkdir\n";
					mkdir( $checkdir );
					chgrp( $checkdir, $SET_FILES_GROUP );
					chmod( $checkdir, 0770 );
				}
			}

			switch ($info["action"]) {
				case "copy":
					echo "Copy File : ".$cmsupdatedir."/".$trgfile." -> ".$ROOT_DIR."/".$trgfile ."\n";
					copy($cmsupdatedir."/".$trgfile, $ROOT_DIR."/".$trgfile);
					chgrp($ROOT_DIR."/".$trgfile, $SET_FILES_GROUP);
					chmod($ROOT_DIR."/".$trgfile, 0770);
					break;
				case "delfile":
					echo "Delete File : ".$ROOT_DIR."/".$trgfile ."\n";
					if(is_file($ROOT_DIR."/".$trgfile)) {
						unlink($ROOT_DIR."/".$trgfile);
					}
					break;
			}
		}
	}

	if (is_file($cmsupdatedir."/cms_update_sql.php")) {
		require $cmsupdatedir."/cms_update_sql.php";
	}

        return array(1,"");
}

?>
