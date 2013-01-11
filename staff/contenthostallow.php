<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_hostallow__id = $_REQUEST["content_hostallow__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_hostallow__name"]){
		$ERROR[]=__("core", "Podaj nazwę adresu");
	}
	if(!$dane["content_hostallow__hosts"]){
		$ERROR[]=__("core", "Podaj listę adresów");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_hostallow__id = content_hostallow_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_hostallow__id = content_hostallow_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_hostallow__id = content_hostallow_delete($dane["content_hostallow__id"]);
		unset($dane);
	}
}
else {
	$content_hostallow__id = $content_hostallow__id ? $content_hostallow__id : "0";
}

if( $content_hostallow__id ) {
	$dane = content_hostallow_dane( $content_hostallow__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_hostallow__id && $content_hostallow__id!="0") {
        $params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_hostallow",
		"function_fetch" => "content_hostallow_fetch_all()",
		"mainkey" => "content_hostallow__id",
                "title" => __("core", "Ograniczanie dostępu"),
                "columns" => array(
                        array( "title"=>"Nazwa adresu", "width"=>"100%", "value"=>"%%{content_hostallow__name}%%", "order"=>1, ),
			array( "title"=>"Status", "align"=>"center", "value"=>"%%{content_hostallow__active}%%", "order"=>1,
				"valuesmatch"=>array( 1=>"<div class=green>aktywny</div>",0=>"<div class=gray>nieaktywny</div>" ),
			),
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
							<a class="btn btn-small btn-info" href="?content_hostallow__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<fieldset class="no-legend">
							<?=sm_inputfield( "text", "Nazwa adresu", "", "dane_content_hostallow__name", "dane[content_hostallow__name]", $dane["content_hostallow__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "checkbox", "Aktywny", "", "dane_content_hostallow__active", "dane[content_hostallow__active]", $dane["content_hostallow__active"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "textarea", "Lista hostów", "poszczególne adresy oddziel przecinkiem. Twój aktualny adres: <u>".$_SERVER["REMOTE_ADDR"]."</u>", "dane_content_hostallow__hosts", "dane[content_hostallow__hosts]", $dane["content_hostallow__hosts"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3);?>
						</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_hostallow__id]" value="<?=$dane["content_hostallow__id"]?>">
							<input type=hidden name="content_hostallow__id" value="<?=$content_hostallow__id?>">
<?		if ($dane["content_hostallow__id"]) {?>
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