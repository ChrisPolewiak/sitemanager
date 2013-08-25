<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_file
 */

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_add( $dane ) {
	$dane["content_file__id"] = "0";
	return content_file_edit( $dane );
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_import( $dane ) {

	$dane = trimall($dane);

	set_time_limit(360);

	if( is_array($_FILES) && is_array($_FILES["upload"]) && is_array($_FILES["upload"]["name"]) ) {
		foreach($_FILES["upload"]["name"] AS $id=>$null) {

			$dane["content_file__filename"] = $_FILES["upload"]["name"][$id];
			$dane["content_file__filetype"] = $_FILES["upload"]["type"][$id];
			$dane["content_file__filesize"] = $_FILES["upload"]["size"][$id];
			$dane["content_file__filedata"] = base64_encode(file_get_contents( $_FILES["upload"]["tmp_name"][$id] ));

			$dane["content_file__filetype"] = $dane["content_file__filetype"] ? $dane["content_file__filetype"] : 0;
			$dane["content_file__filesize"] = $dane["content_file__filesize"] ? $dane["content_file__filesize"] : 0;
			$dane["content_file__private"]  = $dane["content_file__private"] ? 1 : 0;

			$dane["record_create_date"] = $dane["content_file__id"] ? $tmp_dane["record_create_date"] : time();
			$dane["record_create_id"]   = $dane["content_file__id"] ? $tmp_dane["record_create_id"]   : $_SESSION["content_user"]["content_user__id"];
			$dane["record_modify_date"] = time();
			$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

			$dane["content_file__id"] = uuid();
			core_changed_add( $dane["content_file__id"], "content_file", $tmp_content_filecategory="", "add" );

			$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_file VALUES (\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__id"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__id"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filename"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filetype"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filesize"])."',\n";
			$SQL_QUERY .= "NULL,\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filedata"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__preview"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__infoname"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__type"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__private"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
			$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
			$SQL_QUERY .= ")\n";

			try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_import()",$SQL_QUERY,$e); }

			content_cache_delete( "content_file", $content_file__id );
		}
	}
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_file__id"]) {
		$tmp_dane = content_file_dane( $dane["content_file__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_file__id"], "content_file", "", "edit" );
	}
	else {
		$dane["content_file__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_file__id"], "content_file", "", "add" );
	}

	/*
	 * single file
	 */
	if( is_file($_FILES["upload"]["tmp_name"]) ) {
		$dane["content_file__filename"] = $_FILES["upload"]["name"];
		$dane["content_file__filetype"] = $_FILES["upload"]["type"];
		$dane["content_file__filesize"] = $_FILES["upload"]["size"];
		$dane["content_file__filedata"] = base64_encode(file_get_contents( $_FILES["upload"]["tmp_name"] ));
	}
	/*
	 * download url
	 */
	elseif( $dane["content_file_fileupload"] ) {
		$dane["content_file__filename"] = basename($dane["content_file_fileupload"]);

		$fp = fopen($dane["content_file_fileupload"],"r");
		$meta = stream_get_meta_data($fp);
		foreach($meta["wrapper_data"] AS $k=>$v) {
			if(preg_match("/^Content-Type: (.+)/", $v, $tmp)) {
				$dane["content_file__filetype"] = $tmp[1];
			}
			if(preg_match("/^Content-Length: (.+)/", $v, $tmp)) {
				$dane["content_file__filesize"] = $tmp[1];
			}
		}
		$contentupload = fread($fp, $dane["content_file__filesize"] );
		$dane["content_file__filesize"] = strlen( $contentupload );
		$dane["content_file__filedata"] = $contentupload;
		fclose($fp);
	}
	elseif ($dane["content_file__id"]) {
		$dane["content_file__filename"] = $tmp_dane["content_file__filename"];
		$dane["content_file__filetype"] = $tmp_dane["content_file__filetype"];
		$dane["content_file__filesize"] = $tmp_dane["content_file__filesize"];
		$dane["content_file__filedata"] = $tmp_dane["content_file__filedata"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_file__filetype"] = $dane["content_file__filetype"] ? $dane["content_file__filetype"] : 0;
	$dane["content_file__filesize"] = $dane["content_file__filesize"] ? $dane["content_file__filesize"] : 0;
	$dane["content_file__private"]  = $dane["content_file__private"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_file VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filename"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filetype"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filesize"])."',\n";
	$SQL_QUERY .= "NULL,\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__filedata"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__preview"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__infoname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__type"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_file__private"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_edit()",$SQL_QUERY,$e); }

	return $dane["content_file__id"];
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_delete( $content_file__id ) {

	core_changed_add( $content_access__id, "content_access", $deleted="", "del" );

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_file \n";
	$SQL_QUERY .= "WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_dane( $content_file__id ) {

	$SQL_QUERY = "SELECT * FROM ".DB_TABLEPREFIX."_content_file WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_fetch_all( $content_file__type="", $order="" , $kategoria="", $start=0, $limit="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_file \n";
	
	if ($content_file__type!=""){
		$SQL_QUERY .= "WHERE content_file__type='". sm_secure_string_sql( $content_file__type)."' \n";
	}
	if ($limit){
		$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $start).", ". sm_secure_string_sql( $limit)." \n";
	}
	
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_file
 * @package		sql
 * @version		5.0.0
*/
function content_file_fetch_by_contentcategory( $content_category__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_file \n";
	$SQL_QUERY .= "WHERE content_category__id='". sm_secure_string_sql( $content_category__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file_fetch_by_contentcategory()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>