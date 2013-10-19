<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$access_type_id = $_REQUEST["access_type_id"];

if(!is_array($ERROR))
{
	if ( isset($action["edit"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		foreach($dane AS $k=>$v)
			core_config_edit( array("config_name"=>$k, "config_value"=>$v) );
	}
}

include "_page_header5.php";

if($result=core_config_fetch_all())
{
	while($row=$result->fetch(PDO::FETCH_ASSOC))
		$dane[ $row["config_name"] ] = $row["config_value"];
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
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa aplikacji",
										"help"=>"",
										"id"=>"dane_SERVER_NAME",
										"name"=>"dane[SERVER_NAME]",
										"value"=>$dane["SERVER_NAME"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa grupy dla Apache",
										"help"=>"",
										"id"=>"dane_SET_FILES_GROUP",
										"name"=>"dane[SET_FILES_GROUP]",
										"value"=>$dane["SET_FILES_GROUP"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Adres administratora",
										"help"=>"Na jaki adres wysyłać problemy w działaniu aplikacji",
										"id"=>"dane_MAIL_ADDR_ADMIN",
										"name"=>"dane[MAIL_ADDR_ADMIN]",
										"value"=>$dane["MAIL_ADDR_ADMIN"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</fieldset>
							</div>

							<div id="tabs-2" class="tab-pane">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Długość cache dla obrazków",
										"help"=>"Po jakim czasie miniatury obrazków są przeładowywane",
										"id"=>"dane_CACHE_IMAGE_TIMEOUT",
										"name"=>"dane[CACHE_IMAGE_TIMEOUT]",
										"value"=>$dane["CACHE_IMAGE_TIMEOUT"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa witryny",
										"help"=>"Pojawi się w tytule strony",
										"id"=>"dane_SITE_TITLE",
										"name"=>"dane[SITE_TITLE]",
										"value"=>$dane["SITE_TITLE"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"textarea",
										"title"=>"Opis witryny",
										"help"=>"Pojawi się w polu Description w nagłówku strony",
										"id"=>"dane_SITE_DESCRIPTION",
										"name"=>"dane[SITE_DESCRIPTION]",
										"value"=>$dane["SITE_DESCRIPTION"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>3,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"textarea",
										"title"=>"Słowa kluczowe",
										"help"=>"Pojawią się w polu Keywords w nagłówku strony",
										"id"=>"dane_SITE_KEYWORDS",
										"name"=>"dane[SITE_KEYWORDS]",
										"value"=>$dane["SITE_KEYWORDS"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>3,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</fieldset>
							</div>

							<div id="tabs-3" class="tab-pane">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"SiteManager Support ID Code",
										"help"=>"",
										"id"=>"dane_SM_SUPPORTIDCODE",
										"name"=>"dane[SM_SUPPORTIDCODE]",
										"value"=>$dane["SM_SUPPORTIDCODE"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"SiteManager Support Registration Code",
										"help"=>"",
										"id"=>"dane_SM_SUPPORTREGCODE",
										"name"=>"dane[SM_SUPPORTREGCODE]",
										"value"=>$dane["SM_SUPPORTREGCODE"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</fieldset>
							</div>
						</div>

<?
if (sm_core_content_user_accesscheck($access_type_id."_WRITE"))
{
?>
						<div class="btn-toolbar">
							<a class="btn btn-mini btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
						</div>
<?
}
?>
<script>
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<? include "_page_footer5.php" ?>