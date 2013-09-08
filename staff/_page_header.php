<?
require "_header.php";
?>
<body>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<div class="sm-admin">
		<div class="sm-admin-header">
			<div class="sm-admin-header-logo">
				<a href="/<?=$SM_ADMIN_PANEL?>"><img src="/staff/img/sitemanager-logo-white.png" alt="sitemanager" border=0></a>
			</div>
			<div class="sm-admin-header-user">
				<?=__("core", "Zalogowany użytkownik")?>: <b><?=$_SESSION["content_user"]["content_user__username"]?></b><br>
				<ul>
					<li><a href="<?=$ENGINE?>/?logout=1"><?=__("core", "Wyloguj się")?></a></li>
					<li><?=__("core", "Ustawienia konta")?>,</li>
<?/*
					<li>
						<form>
							<?=__("core", "Język")?>:
							<select name="admin_lang" OnChange="submit()" style="width:50px">
<?
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		echo "<option value=\"$k\"".($k==$admin_lang?" selected":"")."> $k </option>";
	}
?>
							</select>
						</form>
					</li>
*/?>
				</ul>
			</div>
		</div>
		<div class="sm-admin-info">
			<div>
				<b><?=$SITE_TITLE?> / <?=$menu[$menu_id]["name"]?></b>
			</div>
		</div>
		<div class="sm-admin-content">
			<div class="sm-admin-content-left">
				<div class="sm-admin-content-menu">
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
		$jquery_accordion_counter[$k] = $counter;
		$counter++;
?>
					<p><a href="#"><span><?=$v["name"]?></span></a></p>
<?
		if($v["submenu"]) {
?>
					<div>
						<ul>
<?
			foreach($v["submenu"] AS $k2=>$v2) {
				$_status="";
				$staff_page_config = array();
				if (is_array($menu[$k2]["config"])){
					$staff_page_config = $menu[$k2]["config"];
				}
				if ($staff_page_config["menu_disabled"])
					continue;
				if($k2==$menu_id) {
					$_status="active";
				}
?>
							<li>
								<a href="<?=$ENGINE?>/<?=$v2["url"]?>" class="<?=$_status?>"><span><?=$v2["name"]?></span></a>
<?
				if($k2==$menu_id && $v2["submenu"]) {
					unset($_submenu);
					if($v2["submenu"]["function"]){
						eval("\$_result = ".$v2["submenu"]["function"]."; ");
						if($_result){
							while($_row=$_result->fetch(PDO::FETCH_ASSOC)){
								$_submenu[ $_row[$v2["submenu"]["key"]] ] = $_row[$v2["submenu"]["name"]];
							}
						}
					}
					elseif(is_array( $$v2["submenu"]["array"] )) {
						foreach($$v2["submenu"]["array"] AS $_k=>$_v){
							$_submenu[ $_k ] = $_v;
						}
					}
					if(is_array($_submenu)) {
?>
								<ul>
<?
						foreach($_submenu AS $_k=>$_v){
							$__status=0;
							if($subidv==$_k) {
								$__status="active";
							}
?>
									<li><a class="<?=$__status?>" href="?subidk=<?=$v2["submenu"]["key"]?>&subidv=<?=$_k?>"><?=$_v?></a></li>
<?
						}
?>
								</ul>
<?
					}
				}
?>
							</li>
<?
			}
?>
						</ul>
					</div>
<?
		}
	}
?>
				</div>

<?
	if($dane) {
		include "__record_history.php";
	}
?>

			</div>
<script language="JavaScript"><!--

jQuery(".sm-admin-content-menu").accordion({
	autoheight:false,
	animated:'bounceslide',
	active:<?=isset($jquery_accordion_counter[ $admin_menu_parent ])?$jquery_accordion_counter[ $admin_menu_parent ]:'false'?>
});

//--></script>
			<div class="sm-admin-content-right">
<?
	if( $GLOBALS["ERROR"] ) {
?>
				<div class="sm-admin-content-message sm-admin-content-message-error">
					<h1>BŁĄD</h1>
					<p>
						<ul><li><?=join("</li><li>",$GLOBALS["ERROR"])?></li></ul>
					</p>
				</div>
<?
	}
?>
<?
	if( $GLOBALS["SUBMIT_STATUS"] ){
?>
				<div class="sm-admin-content-message sm-admin-content-message-info">
					<p>
						<?=$GLOBALS["SUBMIT_STATUS"]?> o godz: <?=date("H:i:s")?>
					</p>
				</div>
<?
	}
?>
				<div class="sm-admin-content-body">
