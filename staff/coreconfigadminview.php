<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$dane_column = $_REQUEST["dane_column"];
$core_configadminview__id = $_REQUEST["core_configadminview__id"];
$core_configadminview__idcolumn = $_REQUEST["core_configadminview__idcolumn"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_configadminview__id = core_configadminview_add($dane);
	}
	if ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_configadminview__id = core_configadminview_edit($dane);
	}
	if ( isset($action["column_add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_configadminview__idcolumn = core_configadminviewcolumn_add($dane_column);
	}
	if ( isset($action["column_edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_configadminview__idcolumn = core_configadminviewcolumn_edit($dane_column);
	}
}
else {
	$core_configadminview__id = $core_configadminview__id ? $core_configadminview__id : "0";
}

if( $core_configadminview__id ) {
	$dane = core_configadminview_dane( $core_configadminview__id );
}
if( $core_configadminview__idcolumn ) {
	$dane_column = core_configadminviewcolumn_dane( $core_configadminview__idcolumn );
}

include "_page_header5.php";

if (!$core_configadminview__id && $core_configadminview__id!="0") {

	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "core_configadminview",
		"function_fetch" => "core_configadminview_fetch_all()",
		"mainkey" => "core_configadminview__id",
		"columns" => array(
			array( "title"=>__("CORE", "CORE_CONFIGADMINVIEW__FIELD_TAG"), "width"=>"100%", "value"=>"%%{core_configadminview__tag}%%", "order"=>1, ),
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
							<a class="btn btn-small btn-info" href="?core_configadminview__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<div class="row-fluid">
							<div class="span6">

								<fieldset class="no-legend">
<?
	$inputfield_options=array();
	foreach($menu AS $tag=>$menu_item) {
		if($menu_item["level"]) {
			$inputfield_options[ $tag ] = $menu_item["name"];
		}
	}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_TAG"),
										"help"=>"",
										"id"=>"dane_core_configadminview__tag",
										"name"=>"dane[core_configadminview__tag]",
										"value"=>$dane["core_configadminview__tag"],
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
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_DBNAME"),
										"help"=>"",
										"id"=>"dane_core_configadminview__dbname",
										"name"=>"dane[core_configadminview__dbname]",
										"value"=>$dane["core_configadminview__dbname"],
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
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_MAINKEY"),
										"help"=>"",
										"id"=>"dane_core_configadminview__mainkey",
										"name"=>"dane[core_configadminview__mainkey]",
										"value"=>$dane["core_configadminview__mainkey"],
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
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_FUNCTION"),
										"help"=>"",
										"id"=>"dane_core_configadminview__function",
										"name"=>"dane[core_configadminview__function]",
										"value"=>$dane["core_configadminview__function"],
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
										"type"=>"checkbox",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_BUTTON_BACK"),
										"help"=>"",
										"id"=>"dane_core_configadminview__button_back",
										"name"=>"dane[core_configadminview__button_back]",
										"value"=>$dane["core_configadminview__button_back"],
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
										"type"=>"checkbox",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_BUTTON_ADDNEW"),
										"help"=>"",
										"id"=>"dane_core_configadminview__button_addnew",
										"name"=>"dane[core_configadminview__button_addnew]",
										"value"=>$dane["core_configadminview__button_addnew"],
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
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_ROWPERPAGE"),
										"help"=>"",
										"id"=>"dane_core_configadminview__rowperpage",
										"name"=>"dane[core_configadminview__rowperpage]",
										"value"=>$dane["core_configadminview__rowperpage"],
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

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<input type=hidden name="dane[core_configadminview__id]" value="<?=$dane["core_configadminview__id"]?>">
									<input type=hidden name="core_configadminview__id" value="<?=$dane["core_configadminview__id"]?>">
<?		if ($dane["core_configadminview__id"]) {?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
								</div>
<?	} ?>

							</div>
							<div class="span6">
								
								<div class="fieldset-title">
									<div><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_FIELDS")?></div>
								</div>
								<fieldset class="no-legend">
									<table class="table">
										<thead>
											<tr>
												<th><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_TITLE")?></th>
												<th><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_WIDTH")?></th>
												<th><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_VALUE")?></th>
												<th><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_ALIGN")?></th>
												<th><?=__("CORE", "CORE_CONFIGADMINVIEW__FIELD_VIEW_ORDER")?></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
<?
	if($result=core_configadminviewcolumn_fetch_by_adminview( $core_configadminview__id )) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
?>
											<tr>
												<td><?=$row["core_configadminviewcolumn__title"]?></td>
												<td><?=$row["core_configadminviewcolumn__width"]?></td>
												<td><?=$row["core_configadminviewcolumn__value"]?></td>
												<td><?=$row["core_configadminviewcolumn__align"]?></td>
												<td><?=$row["core_configadminviewcolumn__order"]?></td>
												<td><a href="?core_configadminview__id=<?=$core_configadminview__id?>&core_configadminview__idcolumn=<?=$row["core_configadminview__idcolumn"]?>"><i class="icon-edit"></i></a></td>
											</tr>
<?
		}
	}
?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan=6><a href="?core_configadminview__id=<?=$core_configadminview__id?>"><?=__("CORE", "BUTTON__ADD_NEW")?></a></td>
											</tr>
										</tfoot>
									</table>
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_VIEW_TITLE"),
										"help"=>"",
										"id"=>"dane_column_core_configadminviewcolumn__title",
										"name"=>"dane[core_configadminviewcolumn__title]",
										"value"=>$dane["core_configadminviewcolumn__title"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
<?
	$inputfield_options=array();
	for($i=50;$i<=300;$i+=50) {
		$inputfield_options[ $i."px" ] = $i."px";
	}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_VIEW_WIDTH"),
										"help"=>"",
										"id"=>"dane_column_core_configadminviewcolumn__width",
										"name"=>"dane[core_configadminviewcolumn__width]",
										"value"=>$dane["core_configadminviewcolumn__width"],
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
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_VIEW_VALUE"),
										"help"=>"",
										"id"=>"dane_column_core_configadminviewcolumn__value",
										"name"=>"dane[core_configadminviewcolumn__value]",
										"value"=>$dane["core_configadminviewcolumn__value"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>$inputfield_options,
										"xss_secured"=>true
									));?>
<?
	$inputfield_options=array();
	$_align = array(
		"" =>__("CORE", "TXT__ALIGN_NONE"),
		"l"=>__("CORE", "TXT__ALIGN_LEFT"),
		"c"=>__("CORE", "TXT__ALIGN_CENTER"),
		"r"=>__("CORE", "TXT__ALIGN_RIGHT")
	);
	foreach($_align AS $k=>$v) {
		$inputfield_options[ $k ] = $v;
	}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_VIEW_ALIGN"),
										"help"=>"",
										"id"=>"dane_column_core_configadminviewcolumn__align",
										"name"=>"dane[core_configadminviewcolumn__align]",
										"value"=>$dane["core_configadminviewcolumn__align"],
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
										"type"=>"checkbox",
										"title"=>__("CORE","CORE_CONFIGADMINVIEW__FIELD_VIEW_ORDER"),
										"help"=>"",
										"id"=>"dane_column_core_configadminviewcolumn__order",
										"name"=>"dane[core_configadminviewcolumn__order]",
										"value"=>$dane["core_configadminviewcolumn__order"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>$inputfield_options,
										"xss_secured"=>true
									));?>
<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
									<div class="btn-toolbar">
										<input type=hidden name="dane_column[core_configadminview__idcolumn]" value="<?=$dane_column["core_configadminview__idcolumn"]?>">
										<input type=hidden name="core_configadminview__idcolumn" value="<?=$dane_column["core_configadminview__idcolumn"]?>">
										<input type=hidden name="dane_column[core_configadminview__id]" value="<?=$core_configadminview__id?>">
<?		if ($dane_column["core_configadminview__idcolumn"]) {?>
										<a class="btn btn-mini btn-info" id="action-column_edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
										<a class="btn btn-mini btn-danger" id="action-column_delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
										<a class="btn btn-mini btn-info" id="action-column_add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?	}?>
									</div>
<? } ?>
								</fieldset>
							</div>
						</div>
<script>
$('#action-column_edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[column_edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-column_delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[column_delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-column_add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[column_add]" value=1>');
	$('#sm-form').submit();
});
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
