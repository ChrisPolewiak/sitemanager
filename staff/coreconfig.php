<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$access_type_id = $_REQUEST["access_type_id"];

if(!is_array($ERROR)) {
	if ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		foreach($dane AS $k=>$v){
			core_config_edit( array("config_name"=>$k, "config_value"=>$v) );
		}
	}
}

include "_page_header5.php";
if($result=core_config_fetch_all()){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		$dane[ $row["config_name"] ] = $row["config_value"];
	}
}

$dane = htmlentitiesall($dane);

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

<script>
$('#tabs').ready(function() {
	$('#tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
</script>

						<ul class="nav nav-tabs" id="tabs">
							<li class="active"><a href="#tabs-1"><?=__("core", "Konfiguracja aplikacji")?></a></li>
							<li><a href="#tabs-2"><?=__("core", "Zmienne systemowe")?></a></li>
							<li><a href="#tabs-3"><?=__("core", "Rejestracja")?></a></li>
						</ul>

						<div class="tab-content">
							<div id="tabs-1" class="tab-pane active">
								<fieldset class="no-legend">
									<?=sm_inputfield( "text", "Nazwa aplikacji", "", "dane_SERVER_NAME", "dane[SERVER_NAME]", $dane["SERVER_NAME"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Nazwa grupy dla Apache", "", "dane_SET_FILES_GROUP", "dane[SET_FILES_GROUP]", $dane["SET_FILES_GROUP"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Adres administratora", "Na jaki adres wysyłać problemy w działaniu aplikacji", "dane_MAIL_ADDR_ADMIN", "dane[MAIL_ADDR_ADMIN]", $dane["MAIL_ADDR_ADMIN"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
								</fieldset>
							</div>

							<div id="tabs-2" class="tab-pane">
								<fieldset class="no-legend">
									<?=sm_inputfield( "text", "Długość cache dla obrazków", "Po jakim czasie miniatury obrazków są przeładowywane", "dane_CACHE_IMAGE_TIMEOUT", "dane[CACHE_IMAGE_TIMEOUT]", $dane["CACHE_IMAGE_TIMEOUT"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Nazwa witryny", "Pojawi się w tytule strony", "dane_SITE_TITLE", "dane[SITE_TITLE]", $dane["SITE_TITLE"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "textarea", "Opis witryny", "Pojawi się w polu Description w nagłówku strony", "dane_SITE_DESCRIPTION", "dane[SITE_DESCRIPTION]", $dane["SITE_DESCRIPTION"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3);?>
									<?=sm_inputfield( "textarea", "Słowa kluczowe", "Pojawią się w polu Description w nagłówku strony", "dane_SITE_KEYWORDS", "dane[SITE_KEYWORDS]", $dane["SITE_KEYWORDS"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3);?>
								</fieldset>
							</div>

							<div id="tabs-3" class="tab-pane">
								<fieldset class="no-legend">
									<?=sm_inputfield( "text", "SiteManager Support ID Code", "Pojawi się w tytule strony", "dane_SM_SUPPORTIDCODE", "dane[SM_SUPPORTIDCODE]", $dane["SM_SUPPORTIDCODE"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "SiteManager Support Registration Code", "Pojawi się w tytule strony", "dane_SM_SUPPORTREGCODE", "dane[SM_SUPPORTREGCODE]", $dane["SM_SUPPORTREGCODE"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
								</fieldset>
							</div>
						</div>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<a class="btn btn-mini btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
						</div>
<? } ?>
<script>
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<? include "_page_footer5.php" ?>
