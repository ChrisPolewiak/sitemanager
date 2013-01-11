<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_crontab__id = $_REQUEST["content_crontab__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_crontab__name"]){
		$ERROR[]=__("core", "Podaj nazwę skryptu");
	}
	if(!$dane["content_crontab__mhdmd"]){
		$ERROR[]=__("core", "Podaj konfigurację");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_crontab__id = content_crontab_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_crontab__id = content_crontab_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
        content_crontab_delete($content_crontab__id);
		unset($content_crontab__id);
	}
}
else {
	$content_crontab__id = $content_crontab__id ? $content_crontab__id : "0";
}


if( $content_crontab__id > 0 ) {
	$dane = content_crontab_dane( $content_crontab__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_crontab__id && $content_crontab__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_crontab",
		"function_fetch" => "content_crontab_fetch_all()",
		"mainkey" => "content_crontab__id",
		"columns" => array(
			array( "title"=>__("core", "Nazwa operacji"), "width"=>"100%", "value"=>"%%{content_crontab__name}%%", "order"=>1, ),
			array( "title"=>__("core", "Funkcja"), "width"=>"200", "value"=>"%%{content_crontab__exec}%%", "order"=>1 ),
			array( "title"=>__("core", "Aktywny"), "width"=>"55", "value"=>"%%{content_crontab__active}%%", "align"=>"center",
				"valuesmatch"=>array( 1=>"<div class=green>".__("core", "tak")."</div>",0=>"<div class=gray>".__("core", "nie")."</div>" ),
			),
			array( "title"=>__("core", "Ostatnie uruchomienie"), "width"=>"110", "value"=>"%%{content_crontab__lastrunat}%%", "order"=>1 ),
			array( "title"=>__("core", "Wykonano"), "width"=>"60", "value"=>"%%{content_crontab__laststatus}%%", "align"=>"center",
				"valuesmatch"=>array( 1=>"<div class=green>".__("core", "tak")."</div>",0=>"<div class=red>".__("core", "nie")."</div>" ),
			),
			array( "title"=>__("core", "Ostatni status"), "width"=>"150", "value"=>"%%{content_crontab__lastmessage}%%", "order"=>1 ),
		),
		"row_per_page_default" => 100,
	);
	include "_datatable_list5.php";
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_crontab__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<fieldset class="no-legend">
							<?=sm_inputfield( "text", "Nazwa operacji", "używana wewnętrznie", "dane_content_crontab__name", "dane[content_crontab__name]", $dane["content_crontab__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "checkbox", "Aktywny", "Czy dana operacja jest aktywna czy nie", "dane_content_crontab__active", "dane[content_crontab__active]", $dane["content_crontab__active"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "textarea", "Konfiguracja", "(MHDMD)- zgodna z systemem CRON", "dane_content_crontab__mhdmd", "dane[content_crontab__mhdmd]", $dane["content_crontab__mhdmd"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
<?
	$inputfield_options=array();
	$inputfield_options[] = "---";
	foreach($CMSCRONTAB AS $value) {
		$inputfield_options[ $value["name"] ] = $value["name"]." - ".$value["info"];
	}
?>
							<?=sm_inputfield( "select", "Wybierz funkcję z listy", "Funkcje systemowe i z pluginów", "dane_content_crontab__exec", "dane[content_crontab__exec]", $dane["content_crontab__exec"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
						</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_crontab__id]" value="<?=$dane["content_crontab__id"]?>">
							<input type=hidden name="content_crontab__id" value="<?=$content_crontab__id?>">
<?		if ($dane["content_crontab__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
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

<? include "_page_footer5.php" ?>