<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$lang = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : "pl";
if(! isset($SM_TRANSLATION_LANGUAGES[$lang])) {
	$lang = "pl";
}

if ( isset($action["edit"]) ) {
	$dane=trimall($dane);
}

if(!is_array($ERROR)) {
	if ( isset($action["edit"]) ) {
		$language_code = $_REQUEST["language"];
		$dane = $_REQUEST["dane"];

		$lang_file = $language_code.".ini.php";
		$fp = fopen($INCLUDE_DIR."/lang/_".$lang_file, "w");
		fputs($fp,"<"."?php\n/"."*\n");
		fputs($fp,"\n");
		fputs($fp,"# Language definitions for $language_code (".$SM_TRANSLATION_LANGUAGES[$language_code].")\n");
		fputs($fp,"\n");
		fputs($fp,"[_DEFINE_]\n");
		fputs($fp,"LANGUAGE_CODE = \"".$language_code."\"\n");
		fputs($fp,"LANGUAGE_NAME = \"".$SM_TRANSLATION_LANGUAGES[$language_code]."\"\n");
		fputs($fp,"\n");
		foreach($dane AS $_section=>$_section_data){
			fputs($fp,"[".$_section."]\n");
			foreach($_section_data AS $_k=>$_v){
				$_v = strip_tags($_v);
				fputs($fp,$_k." = \"".$_v."\"\n");
			}
			fputs($fp,"\n");
		}
		fputs($fp,"*"."/\n?".">");
		fclose($fp);
		rename($INCLUDE_DIR."/lang/".$lang_file, $INCLUDE_DIR."/lang/bak_".$lang_file);
		rename($INCLUDE_DIR."/lang/_".$lang_file, $INCLUDE_DIR."/lang/".$lang_file);
	}				
}

$translation_data = parse_ini_file ($INCLUDE_DIR."/lang/".$lang.".ini.php", $process_sections=true );

include "_page_header5.php";

$dane = htmlentitiesall($dane);

?>
					<form action="" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset>
							<legend><?=__("core", "CORE_TRANSLATION__SECTION_CONFIG")?></legend>
							<div class="row-float">
								<div class="span3">
<?
	$inputfield_options = array();
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		$inputfield_options[ $k ] = $v;
	}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>__("CORE", "CORE_TRANSLATION__FIELD_CONFIG_NAME"),
										"help"=>"",
										"id"=>"language",
										"name"=>"language",
										"value"=>$translation_data["_DEFINE_"]["LANGUAGE_CODE"],
										"size"=>"block-level",
										"disabled"=>false,
										"validation"=>true,
										"prepend"=>false,
										"append"=>false,
										"rows"=>1,
										"options"=>$inputfield_options,
										"xss_secured"=>true
									));?>
								</div>
								<div class="span2">
									<div class="btn-group">
										<br>
										<a class="btn btn-small btn-info" id="action-select"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SELECT")?></a>
									</div>
								</div>
							</div>
						</fieldset>
<?
	foreach( $translation_data AS $translation_section=>$translation_section_data ) {
		if ($translation_section == "_DEFINE_") continue;
?>
						<fieldset>
							<legend><?=__("core", "CORE_TRANSLATION__SECTION")?>: <?=$translation_section?></legend>
<?
		foreach($translation_section_data AS $k=>$v) {
?>
							<div class="row-float">
								<div class="span4">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>__("CORE", "CORE_TRANSLATION__FIELD_NAME"),
										"help"=>"",
										"id"=>"language",
										"name"=>"language",
										"value"=>$k,
										"size"=>"block-level",
										"disabled"=>true,
										"validation"=>true,
										"prepend"=>false,
										"append"=>false,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</div>
								<div class="span8">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>__("CORE", "CORE_TRANSLATION__FIELD_VALUE"),
										"help"=>"",
										"id"=>"dane_".$translation_section."_".$k,
										"name"=>"dane[".$translation_section."][".$k."]",
										"value"=>$v,
										"size"=>"block-level",
										"disabled"=>false,
										"validation"=>false,
										"prepend"=>false,
										"append"=>false,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
								</div>
							</div>
<?
		}
?>
						</fieldset>
<?
	}
?>
<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
						</div>
<? } ?>
<script>
$('#action-select').click(function() {
	window.location = '?lang='+$('#language').val();
});
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>
<? include "_page_footer5.php"; ?>
