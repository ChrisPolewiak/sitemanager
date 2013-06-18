<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_template__id = $_REQUEST["content_template__id"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	$templatedir = $ROOT_DIR."/html/pages". ($dane["content_template__lang"] ? "/".$dane["content_template__lang"] : "");

	$fparts = pathinfo($dane["content_template__srcfile"]);

	if(!$dane["content_template__name"]){
		$ERROR[] = __("core", "Podaj nazwę szablonu");
	}
	if(!$dane["content_template__srcfile"]){
		$ERROR[] = __("core", "Podaj adres pliku z szablonem");
	}
	elseif(! preg_match("/^[\d\w\_\-\.]+$/", $dane["content_template__srcfile"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie podstrony").": ".__("core", "wyłącznie litery, cyfry, myślnik, podkreślenie oraz kropka");
	}
	elseif( $fparts["extension"]!="tpl" && $fparts["extension"]!="php" ) {
		$ERROR[] = __("core", "Nieprawidłowe rozszerzenie - dopuszczalne to .tpl (smarty), .php (PHP)");
	}
	if( $action["add"] && is_file($templatedir."/".$dane["content_template__srcfile"])) {
		$ERROR[] = __("core", "Podany plik już istnieje");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["refresh-list"]) ) {
		$templatedir = $ROOT_DIR."/html/pages";
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$dir = scandir( $templatedir );
		foreach($dir AS $lang){
			if($lang!="." && $lang!="..") {
				if(is_dir($templatedir."/".$lang)){
					$dir2 = scandir( $templatedir."/".$lang );
					foreach($dir2 AS $file2){
						if($file2!="." && $file2!="..") {
							$fparts = pathinfo($file2);
							if($fparts["extension"]=="tpl" || $fparts["extension"]=="php") {
								$systemplatefiles["$lang"."/"."$file2"] = 1;
							}
						}
					}
				}
				elseif(preg_match("/\.tpl$/", $file)) {
					$systemplatefiles["$file2"];
				}
			}
		}
		
		if($result = content_template_fetch_all()){
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				if($row["content_template__lang"]) {
					$sqltemplatefiles[ $row["content_template__lang"]."/".$row["content_template__srcfile"] ] =1;
				}
				else {
					$sqltemplatefiles[ $row["content_template__srcfile"] ] =1;
				}
			}
		}
		
		foreach($systemplatefiles AS $file=>$null){
			if(!$sqltemplatefiles[$file]) {
				if(preg_match("/(.*)\/(.+)/", $file, $tmp)){
					$filedane = array(
						"content_template__name" => $tmp[2],
						"content_template__srcfile" => $tmp[2],
						"content_template__lang" => $tmp[1]
					);
					content_template_add($filedane);
				}
			}
		}
	}
	elseif ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_template__id = content_template_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_template__id = content_template_edit($dane);
		if($daneprev["content_template__lang"] != $dane["content_template__lang"] || $daneprev["content_template__srcfile"] != $dane["content_template__srcfile"]) {
			$prevtemplatedir = $ROOT_DIR."/html/pages". ($daneprev["content_template__lang"] ? "/".$daneprev["content_template__lang"] : "");
			unlink($prevtemplatedir."/".$daneprev["content_template__srcfile"]);
		}
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_template_delete($content_template__id);
		unset($content_template__id);
	}
	if( isset($action["add"]) || isset($action["edit"])) {
		if(!$ERROR) {
			$fp = fopen( $templatedir."/".$dane["content_template__srcfile"],"w");
			fputs($fp, $dane["content_template__source"]);
			fclose($fp);
			@chgrp( $templatedir."/".$dane["content_template__srcfile"], $SET_FILES_GROUP);
			@chmod( $templatedir."/".$dane["content_template__srcfile"], 0664);
		}
	}
}
else {
	$content_template__id = $content_template__id ? $content_template__id : "0";
}

if( $content_template__id ) {
	$dane = content_template_dane( $content_template__id );
	$templatedir = $ROOT_DIR."/html/pages". ($dane["content_template__lang"] ? "/".$dane["content_template__lang"] : "");
	if(is_file($templatedir."/".$dane["content_template__srcfile"])) {
		$dane["content_template__source"] = join("", file($templatedir."/".$dane["content_template__srcfile"]) );
		$dane["content_template__source"] = htmlentitiesall($dane["content_template__source"]);
	}
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_template__id && $content_template__id!="0") {

        $params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_template",
		"function_fetch" => "content_template_fetch_all()",
		"mainkey" => "content_template__id",
                "columns" => array(
                        array( "title"=>__("core", "Nazwa szablonu"), "width"=>"100%", "value"=>"%%{content_template__name}%%", "order"=>"name", ),
                        array( "title"=>__("core", "Nazwa pliku szablonu"), "width"=>"300", "value"=>"%%{content_template__lang}/{content_template__srcfile}%%", "order"=>"src", ),
                ),
		"row_per_page_default" => 100,
        );

	$datatable_btn_add = array();
	$datatable_btn_add[] = array(
		"color-class"=>"info",
		"url"=>"?action[refresh-list]=1",
		"icon"=>"refresh",
		"title"=>"Zaktualizuj listę plików",
	);

        include "_datatable_list5.php";

}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_template__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="row-float">
							<div class="span8">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"textarea",
										"title"=>"Zawartość szablonu",
										"help"=>"Kod szablonu",
										"id"=>"dane_content_template__source",
										"name"=>"dane[content_template__source]",
										"value"=> $dane["content_template__source"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>30,
										"options"=>"",
										"xss_secured"=>false
									));?>
									<script>
										// dane_content_template__source.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
									</script>
									
								</fieldset>

							</div>
							<div class="span4">

								<div class="fieldset-title">
									<div>Parametry</div>
								</div>
								<fieldset class="no-legend">
<?
	$inputfield_options=array();
	$inputfield_options[""]="dowolny";
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		$inputfield_options[ $k ]=$v;
	}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>"Język",
										"help"=>"",
										"id"=>"dane_content_template__lang",
										"name"=>"dane[content_template__lang]",
										"value"=> $dane["content_template__lang"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>$inputfield_options,
										"xss_secured"=>true
									));?>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa szablonu",
										"help"=>"",
										"id"=>"dane_content_template__name",
										"name"=>"dane[content_template__name]",
										"value"=> $dane["content_template__name"],
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
										"title"=>"Nazwa pliku szablonu",
										"help"=>"",
										"id"=>"dane_content_template__srcfile",
										"name"=>"dane[content_template__srcfile]",
										"value"=> $dane["content_template__srcfile"],
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

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="daneprev[content_template__lang]" value="<?=$dane["content_template__lang"]?>">
							<input type=hidden name="daneprev[content_template__srcfile]" value="<?=$dane["content_template__srcfile"]?>">
							<input type=hidden name="dane[content_template__id]" value="<?=$dane["content_template__id"]?>">
							<input type=hidden name="content_template__id" value="<?=$content_template__id?>">
<?		if ($dane["content_template__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
						</div>
<?	} ?>

<script>
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[add]" value=1>');
	$('#sm-form').submit();
});
</script>
</form>

<?
}
?>

<? include "_page_footer5.php"; ?>
