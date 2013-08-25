<?
include "_page_header5.php";
?>
					<h3>SiteManager - Panel Administracyjny</h3>
<?
$_set_table = false;
foreach($menu AS $k=>$v) {
	$staff_page_config = array();
	if(is_array($menu[$k]["config"])){
		$staff_page_config = $menu[$k]["config"];
	}
	if ($staff_page_config["menu_disabled"])
		continue;

	if($v["level"] == 0 && $_set_table) {
		echo "</dl>";
		echo "</fieldset>";
		$_set_table = false;
	}
	if ($v["level"] == 0) {
?>
					<div class="fieldset-title">
						<div><?=$v["name"]?></div>
					</div>
					<fieldset class="no-legend">
						<dl class="float">
<?
	}
	else {
		$v["url"] = $v["url"] ? $v["url"] : $v["file"];
?>
<div class="item">
	<dt>
		<a href="<?=$ENGINE?>/<?=$v["url"]?>" id="index-menu-<?=$k?>" rel="popover" data-placement="bottom" data-content="<?=$v["info-short"]?>" title="<?=$v["name"]?>"><?=$v["name"]?></a>
	</dt>
<?
		if($v["info-short"]) {
#			echo "<dd>".$v["info-short"]."</dd>\n";
?>
<script>
$('#index-menu-<?=$k?>').popover();
</script>
<?
		}
		echo "</div>\n";
	}

	if($v["level"] == 0) {
		#echo "<table cellspacing=1 cellpadding=1>";
		$_set_table = true;
	}
}
echo "</dl>";
echo "</fieldset>"; 
?>
					<div class="fieldset-title">
						<div><?=__("core", "INDEX__SYSINFO")?></div>
					</div>
					<fieldset class="no-legend">
						<b>CMS Core:</b> <?=__("core", "INDEX__CMS_VERSION")?> <?=$SOFTWARE_INFORMATION["version"]?> (<?=("data")?>: <?=$SOFTWARE_INFORMATION["date"]?>)<br>
<?
if(is_array($SM_PLUGINS)) {
	foreach ($SM_PLUGINS AS $k=>$v) {
?>
						<?=__("core", "INDEX__PLUGIN_VERSION", $v["name"], $v["version"], $v["moddate"])?><br>
<?
	}
}
?>
						<a href="<?=$ENGINE?>/sysinfo.php"><?=__("core", "INDEX__MORE_SYSINFO")?></a><br>
					</fieldset>

					<div class="fieldset-title">
						<div><?=__("core", "ADMIN_LOGED_USERS")?></div>
					</div>
					<fieldset class="no-legend">
						<b>Ilość użytkowników korzystających z systemu:</b> <?=core_session_count_all()?> - <a href="<?=$ENGINE?>/coresession.php">Lista aktywnych sesji</a><br>
					</fieldset>
<? include "_page_footer5.php"; ?>