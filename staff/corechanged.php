<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$core_changed__id = $_REQUEST["core_changed__id"];

if(!is_array($ERROR)) {
	if ( isset($action["restore"]) ) {
//		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
//		$tmp = core_changed_dane($dane["core_changed__id"]);
//		$core_changed__id = core_changed_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		core_changed_delete($core_changed__id);
		unset($core_changed__id);
	}
}
else {
	$core_changed__id = $core_changed__id ? $core_changed__id : "0";
}

if( $core_changed__id ) {
	$dane = core_changed_dane( $core_changed__id );
}

include "_page_header5.php";

if (!$core_changed__id && $core_changed__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "core_changed",
		"mainkey" => "core_changed__id",
		"columns" => array(
			array( "title"=>__("core", "CORE_DELETED__FIELD_TABLE"), "width"=>"100%", "value"=>"%%{core_changed__table}%%", "order"=>1, ),
			array( "title"=>__("core", "CORE_DELETED__FIELD_ID"), "align"=>"left", "value"=>"%%{core_changed__tableid}%%", "order"=>1, ),
			array( "title"=>__("core", "CORE_DELETED__FIELD_DELETED_DATE"), "align"=>"left", "value"=>"%%[date]{record_create_date}%%", "order"=>1, ),
		),
		"row_per_page_default" => 100,
		"function_fetch" => "core_changed_fetch_all()",
	);

	include "_datatable_list5.php";
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">


						<div class="fieldset-title">
							<div><?=__("core", "CORE_DELETED__DELETED_OBJECT")?></div>
						</div>
						<fieldset class="no-legend">
							<?=sm_inputfield( "text", __("CORE","CORE_DELETED__FIELD_TABLE"), "", "dane_core_changed__table", "dane[core_changed__table]", $dane["core_changed__table"], "block-level", $disabled=true, $validation=false, $prepend=false, $append=false, $rows=1);?>
							<?=sm_inputfield( "text", __("CORE","CORE_DELETED__FIELD_ID"), "", "dane_core_changed__tableid", "dane[core_changed__tableid]", $dane["core_changed__tableid"], "block-level", $disabled=true, $validation=false, $prepend=false, $append=false, $rows=1);?>
<?
$olddata = var_export( json_decode( stripslashes( $dane["core_changed__olddata"] ) ),true );
?>
							<?=sm_inputfield( "textarea", __("CORE","CORE_DELETED__FIELD_DATA"), "", "dane_core_changed__tableid", "dane[core_changed__olddata]", $olddata, "block-level", $disabled=true, $validation=false, $prepend=false, $append=false, $rows=10);?>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
							<input type=hidden name="dane[core_changed__id]" value="<?=$dane["core_changed__id"]?>">
							<input type=hidden name="core_changed__id" value="<?=$dane["core_changed__id"]?>">
							<div class="btn-toolbar">
<?		if ($dane["core_changed__id"]) {?>
								<a class="btn btn-normal btn-info" id="action-restore"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__RESTORE")?></a>
								<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		}?>
						</div>
<?	} ?>
<script>
$('#action-restore').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[restore]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
?>

<? include "_page_footer5.php"; ?>
