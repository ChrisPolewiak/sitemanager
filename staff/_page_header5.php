<?

require "_header5.php";

?>
<body>

<script>
function confDelete() {
	str = '<?=_("CORE", "TXT__RECORD_DELETE_CONFIRM")?>';
	return confirm(str);
}
</script>

	<div class="navbar navbar-static-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="/<?=$SM_ADMIN_PANEL?>"><img src="/staff/img/sitemanager-logo-white.png" alt="sitemanager" border=0 style="position:absolute;margin-top:-5px"></a>
				<ul class="nav pull-right">
					<li>
						<a href="/<?=$SM_ADMIN_PANEL?>/self_account.php"><?=__("core", "HEADER__LOGED_USER")?>: <b><?=$_SESSION["content_user"]["content_user__firstname"]?> <?=$_SESSION["content_user"]["content_user__surname"]?></b></a>
					</li>
					<li><a href="<?=$ENGINE?>/?logout=1"><i class="icon-off"></i>&nbsp;<?=__("core", "BUTTON__LOGOUT")?></a></li>
				</ul>
					
			</div>
		</div>
	</div>

	<div class="container" id="body">
		<ul class="breadcrumb">
			<li><a href="/<?=$SM_ADMIN_PANEL?>"><?=$SITE_TITLE?></a> <span class="divider">/</span></li>
			<li class="active"><?=$menu[$menu_id]["name"]?></li>
		</ul>

		<div class="row-fluid">
			<div class="span2">
				<ul class="nav nav-list" id="sm-main-menu">
<?
	ksort($menu);

	foreach($menu AS $k=>$v){
		if ($v["level"]) {
			$menutree[ $v["level"] ]["submenu"][$k] = array(
				"name"=>$v["name"],
				"url" =>$v["url"] ? $v["url"] : $v["file"],
				"config"=>$v["config"],
				"submenu"=>$v["submenu"],
			);
		}
		else {
			$menutree[$k] = array(
				"name"=>$v["name"],
				"url" =>$v["url"] ? $v["url"] : $v["file"],
				"config"=>$v["config"],
			);
		}
	}

	$counter=0;
	foreach($menutree AS $k=>$v) {
		$v["url"] = $v["url"] ? $v["url"] : $v["file"];
		$_status="";
		$staff_page_config = array();
		if (is_array($menu[$k]["config"])){
			$staff_page_config = $menu[$k]["config"];
		}
		if ($staff_page_config["menu_disabled"])
			continue;
		$counter++;
?>
					<li class="nav-header" id="menu-<?=$k?>"><a href="#"><?=$v["name"]?></a></li>
<?
		if($v["submenu"]) {
			foreach($v["submenu"] AS $k2=>$v2) {
				$_status="";
				$staff_page_config = array();
				if (is_array($menu[$k2]["config"])){
					$staff_page_config = $menu[$k2]["config"];
				}
				if ($staff_page_config["menu_disabled"])
					continue;
				$_active="";
				if($k2==$menu_id) {
					$_active="active";
				}
?>
					<li class="<?=$_active?> menu-item menu-item-<?=$k?>"><a href="<?=$ENGINE?>/<?=$v2["url"]?>" class="<?=$_status?>"><?=$v2["name"]?></a></li>
<?
				if(is_array($menu_content_submenu) && $k2==$menu_id) {
?>
<?
					foreach($menu_content_submenu AS $mck=>$mcv) {
						$_mc_active= ($subidv==$mck ? "active" : "");
?>
					<li class="<?=$_mc_active?> menu-item menu-item-<?=$k?> menu-subitem"><a href="<?=$ENGINE?>/<?=$v2["url"]?>?subidv=<?=$mck?>"><?=$mcv?></a></li>
<?
					}
?>
<?
				}
?>
<?
			}
		}
	}
?>
				</ul>
<?
	if($dane) {
		include "__record_history.php";
	}
?>

<script language="JavaScript"><!--
$('#sm-main-menu .menu-item').hide();
$('#sm-main-menu .nav-header').click(function() {
	menuid = this.id.replace('menu-','');
	$('#sm-main-menu .menu-item-'+menuid).toggle();
});
$('#sm-main-menu .menu-item-<?=$menu[$menu_id]["level"]?>').show();
//--></script>

			</div>
			<div class="span10">

<?
	if( $GLOBALS["ERROR"] ) {
?>
				<div class="alert alert-error">
					<?=join("<br>",$GLOBALS["ERROR"])?>
				</div>
<?
	}
?>
<?
	if( $GLOBALS["SUBMIT_STATUS"] ){
?>
				<div class="alert alert-success">
					<?=date("H:i:s")?>: <?=$GLOBALS["SUBMIT_STATUS"]?>
				</div>
<?
	}
?>
				<div class="sm-box">
					<div>
<?	if ($menu[$menu_id]["info-short"]) { ?>
						<div class="alert alert-info">
							<b><?=$menu[$menu_id]["info-short"]?></b><br>
<?		if ($menu[$menu_id]["info-long"]) { ?>
							<?=$menu[$menu_id]["info-long"]?>
<?		} ?>
						</div>
<?	} ?>
