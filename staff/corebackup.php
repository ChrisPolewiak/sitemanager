<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$corebackup = $_REQUEST["corebackup"];

if ( isset($action["backup"]) ) {

	if($corebackup["backup_database"] || $corebackup["backup_sqldata"] || $corebackup["backup_templates"]) {

	set_time_limit(3600);

		if(!is_dir($BACKUP_DIR)){	
			mkdir($BACKUP_DIR);
			chgrp($BACKUP_DIR, $SET_FILES_GROUP);
			chmod($BACKUP_DIR, 0770);
			$fp=fopen($BACKUP_DIR."/.htaccess","w");
			fputs($fp, "Deny from all");
			fclose($fp);
			chgrp($BACKUP_DIR."/.htaccess", $SET_FILES_GROUP);
			chmod($BACKUP_DIR."/.htaccess", 0660);
		}
		$timestamp = time();
		if($corebackup["backup_database"]) {
			$sql_file="$BACKUP_DIR/backup-".$timestamp."-database.tar.gz";
			exec("`which mysqldump` -u$DB_USER -p$DB_PASS $DB_NAME -h$DB_SERVER --add-drop-table | gzip -9 > $sql_file");
			echo "$sql_file<br>";
		}
		if($corebackup["backup_sqldata"]) {
			$cfl_file="$BACKUP_DIR/backup-".$timestamp."-sqldata.tar.gz";
			exec("tar czf $cfl_file $ROOT_DIR/html/sqldata");
			echo "$cfl_file<br>";
		}
		if($corebackup["backup_templates"]) {
			$tpl_file="$BACKUP_DIR/backup-".$timestamp."-templates.tar.gz";
			exec("tar czf $tpl_file $ROOT_DIR/html/css $ROOT_DIR/html/img $ROOT_DIR/html/js $ROOT_DIR/html/pages");
			echo "$tpl_file<br>";
		}
	}
}
if ( isset($action["delete"]) ) {
	$filepath = $BACKUP_DIR."/backup-".urlencode($timestamp)."-".urlencode($type).".tar.gz";
	if(is_file($filepath)) {
		unlink($filepath);
	}
}


include "_page_header5.php";

$dane = htmlentitiesall($dane);

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="fieldset-title">
							<div>Lista kopii zapasowych</div>
						</div>
						<fieldset class="no-legend">
							<table class=table style="width:100%">
								<thead>
									<tr>
										<th>Komentarz</th>
										<th style="width:110px">Data wykonania</th>
										<th style="width:70px">Zawartość</th>
										<th style="width:70px">Rozmiar</th>
										<th style="width:90px">Odtwórz</th>
									</tr>
								</thead>
								<tbody>
<?
$d = dir($BACKUP_DIR);
unset($backupfiles);
while (false !== ($entry = $d->read())) {
	if($entry!="." && $entry!=".." && $entry!=".htaccess") {
		if(preg_match("/backup-(\d+)-(\w+)\..+/",$entry,$tmp)){
			$time = $tmp[1];
			$type = $tmp[2];
			$file = $entry;
			switch($type) {
				case "templates": $type_name = "Szablony"; break;
				case "database": $type_name = "Baza danych"; break;
				case "sqldata": $type_name = "Pliki"; break;
			}
			$filesize = filesize($BACKUP_DIR."/".$entry);

			$backupfiles[$entry]=array(
				"file"=>$file,
				"filesize"=>$filesize,
				"type_name"=>$type_name,
				"type"=>$type,
				"time"=>$time,
			);
		}
	}
}
if(is_array($backupfiles)) {
	krsort($backupfiles);
	foreach($backupfiles AS $k=>$v) {
?>
									<tr>
										<td><b><?=$v["file"]?></b></td>
										<td><?=date("Y-m-d H:i:s", $v["time"])?></td>
										<td><?=$v["type_name"]?></td>
										<td style="text-align:right"><?=$v["filesize"]?></td>
										<td>
											<a href="?action[restore]=1&type=<?=$v["type"]?>&timestamp=<?=$v["time"]?>">odtwórz</a>
											|
											<a href="?action[delete]=1&type=<?=$v["type"]?>&timestamp=<?=$v["time"]?>">usuń</a>
										</td>
									</tr>
<?
	}
}
?>
								</tbody>
							</table>
						</fieldset>

						<div class="fieldset-title">
							<div>Kopia zapasowa</div>
						</div>
						<fieldset class="no-legend">
							<?=sm_inputfield( "checkbox", "Wykonać kopię bazy danych?", "zostanie wykonana pełna kopia bazy danych całego systemu", "corebackup_backup_database", "corebackup[backup_database]", $corebackup["backup_database"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "checkbox", "Wykonać kopię bazy plików?", "zostaną zapisane wszystkie zasoby wgrane na serwer", "corebackup_backup_sqldata", "corebackup[backup_sqldata]", $corebackup["backup_sqldata"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "checkbox", "Wykonać kopię bazy szablonów?", "zostaną zapisane wszystkie szablony", "corebackup_backup_templates", "corebackup[backup_templates]", $corebackup["backup_templates"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
						</fieldset>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<a class="btn btn-mini btn-info" id="action-backup"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BACKUP")?></a>
						</div>
<? } ?>
<script>
$('#action-backup').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[backup]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<? include "_page_footer5.php"; ?>
