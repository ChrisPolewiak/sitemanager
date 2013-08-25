<?

$content_peeklist__sysname = isset($staff_page_config["peeklist_sysname"]) ? $staff_page_config["peeklist_sysname"] : "";
$content_peeklist__plugin = isset($staff_page_config["peeklist_plugin"]) ? $staff_page_config["peeklist_plugin"] : "";

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$content_peeklist = content_peeklist_get_by_name( $content_peeklist__plugin, $content_peeklist__sysname );
if(!$content_peeklist) {
	$str  = "Peeklist '$content_peeklist__sysname' for plugin '$content_peeklist__plugin' not found. ";
	$str .= "<a href=\"/admin/content_peeklist.php?content_peeklist__id=0&dane[content_peeklist__name]=".$content_peeklist__plugin."+-+".$content_peeklist__sysname."&dane[content_peeklist__sysname]=".$content_peeklist__sysname."&dane[content_peeklist__plugin]=".$content_peeklist__plugin."\">Create new one</a>";
	error($str);
}
$content_peeklist__id = $content_peeklist["content_peeklist__id"];

$dane = $_REQUEST["dane"];
$content_peeklistitem__id = $_REQUEST["content_peeklistitem__id"];

$dane["content_peeklist__id"] = $content_peeklist__id;

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_peeklistitem__id = content_peeklistitem_add($dane);
		content_peeklist_rebuild( $content_peeklist__id );
		header("Location: ?content_peeklistitem__id=".$content_peeklistitem__id);
		exit;
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_peeklistitem__id = content_peeklistitem_edit($dane);
		content_peeklist_rebuild( $content_peeklist__id );
		header("Location: ?content_peeklistitem__id=".$content_peeklistitem__id);
		exit;
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_peeklistitem__id = content_peeklistitem_delete($content_peeklistitem__id);
		unset($dane);
		content_peeklist_rebuild( $content_peeklist__id );
		header("Location: ?");
		exit;
	}
}
else {
	$content_peeklistitem__id = $content_peeklistitem__id ? $content_peeklistitem__id : "0";
}

if( $content_peeklistitem__id ) {
	$dane = content_peeklistitem_dane( $content_peeklistitem__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_peeklistitem__id && $content_peeklistitem__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_peeklistitem",
		"function_fetch" => "content_peeklistitem_fetch_by_content_peeklist( \$content_peeklist__id )",
		"mainkey" => "content_peeklistitem__id",
		"title" => "Kategorie wiadomości",
		"row_per_page_default" => 100,
	);

	for($i=1;$i<=10;$i++) {
		$num = $i<10 ? "0".$i : $i;
		if($content_peeklist["content_peeklist__vtitle".$num]) {
			$params["columns"][] = array( "title"=>$content_peeklist["content_peeklist__vtitle".$num], "width"=>"", "value"=>"%%{content_peeklistitem__value".$num."}%%", "order"=>1, );
		}
	}

	include "_datatable_list5.php";
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_peeklistitem__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
<?
	for($i=1;$i<=10;$i++) {
		$num = $i<10 ? "0".$i : $i;
		if($content_peeklist["content_peeklist__vtitle".$num]) {
?>
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> $content_peeklist["content_peeklist__vtitle".$num],
								"help"	=> "",
								"id"	=> "dane_content_peeklistitem__value".$num,
								"name"	=> "dane[content_peeklistitem__value".$num."]",
								"value"	=> $dane["content_peeklistitem__value".$num.""],
								"size"	=> "block-level",
								"disabled" => 0,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => true
							));?>
<?
		}
	}
?>
						</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_peeklistitem__id]" value="<?=$dane["content_peeklistitem__id"]?>">
							<input type=hidden name="content_peeklistitem__id" value="<?=$content_peeklistitem__id?>">
<?		if ($dane["content_peeklistitem__id"]) {?>
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

<? include "_page_footer5.php"; ?>
