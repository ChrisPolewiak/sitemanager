#!/usr/bin/php -q
<?

$engine_files_version["bin/".__FILE__]["version"] = "4.32";
$engine_files_version["bin/".__FILE__]["moddate"] = "2008-11-10";

define("SESSION_DISABLED",true);
require_once("../include/init.inc");

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
	echo "No new version\n\n";
	exit;
}
echo "Update version: $new_version \n";

$cmsupdate = "/tmp/cmsupdate-".time();
mkdir( $cmsupdate );
$cmsupdatedir = $cmsupdate."/".$new_version;
mkdir( $cmsupdatedir );
if (! is_dir($cmsupdatedir)) {
	die("Error Directory create\n");
}

copy( preg_replace("/(%n)/", trim($new_version), $update_url_files), $cmsupdatedir."/cms_update.tgz")
	or die("Error Copying update from server\n");

exec("which tar", $tar);
exec($tar[0]." xzf ".$cmsupdatedir."/cms_update.tgz -C ".$cmsupdate);

if (! is_file($cmsupdatedir."/cms_update.php")) {
	die("Update info not exists\n");
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

?>
