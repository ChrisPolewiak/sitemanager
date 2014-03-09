<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$danecontent_fileshowtypeitem = $_REQUEST["danecontent_fileshowtypeitem"];
$content_fileshowtype__id = $_REQUEST["content_fileshowtype__id"];
$content_fileshowtypeitem__id = $_REQUEST["content_fileshowtypeitem__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	foreach($CONTENT_FILESSHOWTYPE_AVAILABLEOBJECT AS $k=>$v){
		if($v["sysname"] == $dane["content_fileshowtype__sysname"]) {
			$dane["content_fileshowtype__name"] = $v["name"];
		}
	}
}
if ( isset($action["item_add"]) || isset($action["item_edit"]) ) {
	$danecontent_fileshowtypeitem=trimall($danecontent_fileshowtypeitem);
	if(!$danecontent_fileshowtypeitem["content_fileshowtypeitem__name"]){
		$ERROR[]=__("core", "Podaj nazwę elementu listy");
	}
	if(!$danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"]){
		$ERROR[]=__("core", "Podaj nazwę systemową dla elementu listy");
	}
	if(! preg_match("/^[\d\w\-\_]+$/", $danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie systemowej elementu listy");
	}
	$danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"] = strtoupper($dane["content_fileshowtype__sysname"])."_".$danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"];
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_fileshowtype__id = content_fileshowtype_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_fileshowtype__id = content_fileshowtype_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_fileshowtype_delete($content_fileshowtype__id);
		unset($content_fileshowtype__id);
	}
	elseif ( isset($action["item_add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_fileshowtypeitem__id = content_fileshowtypeitem_add($danecontent_fileshowtypeitem);
	}
	elseif ( isset($action["item_edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_fileshowtypeitem__id = content_fileshowtypeitem_edit($danecontent_fileshowtypeitem);
	}
	elseif ( isset($action["item_delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_fileshowtypeitem_delete( $content_fileshowtypeitem__id );
	}
}
else {
	$content_fileshowtype__id = $content_fileshowtype__id ? $content_fileshowtype__id : "0";
}

if( $content_fileshowtype__id ) {
	$dane = content_fileshowtype_dane( $content_fileshowtype__id );
}
if( $content_fileshowtypeitem__id ) {
	$danecontent_fileshowtypeitem = content_fileshowtypeitem_dane( $content_fileshowtypeitem__id );
	$tmp = explode("_",$danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"]);
	$danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"] = $tmp[2];
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_fileshowtype__id && $content_fileshowtype__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_fileshowtype",
		"function_fetch" => "content_fileshowtype_fetch_all()",
		"mainkey" => "content_fileshowtype__id",
		"title" => __("core", "Lista poziomów dostępu"),
		"columns" => array(
			array( "title"=>__("core", "Nazwa wewnętrzna"), "width"=>"200", "value"=>"%%{content_fileshowtype__sysname}%%", "order"=>1, ),
			array( "title"=>__("core", "Nazwa"), "width"=>"100%", "value"=>"%%{content_fileshowtype__name}%%", "order"=>1, ),
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
							<a class="btn btn-small btn-info" href="?content_fileshowtype__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<div class="row-float">
							<div class="span4">
								<fieldset class="no-legend">
<?
	$inputfield_options=array();
	$inputfield_options[]="---";
	foreach($CONTENT_FILESSHOWTYPE_AVAILABLEOBJECT AS $k=>$v){
		$inputfield_options[ $v["sysname"] ] = $v["name"];
	}
?>
									<?=sm_inputfield(array(
										"type"	=> "select",
										"title"	=> "Obiekt z bazy danych",
										"help"	=> "obiekt, do którego przypisane będą zdjęcia",
										"id"	=> "dane_content_fileshowtype__sysname",
										"name"	=> "dane[content_fileshowtype__sysname]",
										"value"	=> $dane["content_fileshowtype__sysname"],
										"size"	=> "block-level",
										"disabled" => $disabled=($content_fileshowtype__id ? "1" : "0"),
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => $inputfield_options,
										"xss_secured" => true
									));?>
								</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<input type=hidden name="dane[content_fileshowtype__id]" value="<?=$dane["content_fileshowtype__id"]?>">
									<input type=hidden name="content_fileshowtype__id" value="<?=$content_fileshowtype__id?>">
<?		if ($dane["content_fileshowtype__id"]) {?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
								</div>
<?	} ?>

							</div>
							<div class="span8">

								<div class="fieldset-title">
									<div>Elementy listy</div>
								</div>
								<fieldset class="no-legend">
<?
	if ($content_fileshowtype__id) {
?>
									<table width="100%" class=table>
										<thead>
											<tr>
												<th><?=__("core", "Nazwa wewnętrzna")?></th>
												<th width=300><?=__("core", "Nazwa")?></th>
												<th width=50><?=__("core", "Domyślna")?></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
<?
		if($result = content_fileshowtypeitem_fetch_by_content_fileshowtype( $content_fileshowtype__id )) {
			$even="";
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$even=$even?0:1;
?>
											<tr class="<?=$even?"even":"odd"?>">
												<td><?=$row["content_fileshowtypeitem__sysname"]?></td>
												<td><?=$row["content_fileshowtypeitem__name"]?></td>
												<td align=center><?=($row["content_fileshowtypeitem__default"]?"<b class=red>".__("core", "TAK")."</b>":__("core", "NIE"))?></td>
												<td><a href="?content_fileshowtype__id=<?=$content_fileshowtype__id?>&content_fileshowtypeitem__id=<?=$row["content_fileshowtypeitem__id"]?>"><i class="icon-edit"></i></a></td>
												<td><a href="?content_fileshowtype__id=<?=$content_fileshowtype__id?>&content_fileshowtypeitem__id=<?=$row["content_fileshowtypeitem__id"]?>&action[item_delete]=1" OnClick="return confirm()"><i class="icon-remove"></i></a></td>
											</tr>
<?
			}
		}
?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan=6><a class="btn btn-mini btn-info" href="?content_fileshowtype__id=<?=$content_fileshowtype__id?>&content_fileshowtypeitem__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "nowy element")?></a></td>
											</tr>
										</tfoot>
									</table>

<?
		if ($danecontent_fileshowtypeitem || isset($content_fileshowtypeitem__id)) {
?>
									<?=sm_inputfield(array(
										"type"	=> "text",
										"title"	=> "Nazwa",
										"help"	=> "",
										"id"	=> "danecontent_fileshowtypeitem_content_fileshowtypeitem__name",
										"name"	=> "danecontent_fileshowtypeitem[content_fileshowtypeitem__name]",
										"value"	=> $danecontent_fileshowtypeitem["content_fileshowtypeitem__name"],
										"size"	=> "block-level",
										"disabled" => ($content_fileshowtypeitem__id ? "1" : "0"),
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => "",
										"xss_secured" => true
									));?>
									<?=sm_inputfield(array(
										"type"	=> "text",
										"title"	=> "Nazwa wewnętrzna",
										"help"	=> "używana w kodzie",
										"id"	=> "danecontent_fileshowtypeitem_content_fileshowtypeitem__sysname",
										"name"	=> "danecontent_fileshowtypeitem[content_fileshowtypeitem__sysname]",
										"value"	=> $danecontent_fileshowtypeitem["content_fileshowtypeitem__sysname"],
										"size"	=> "large",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => strtoupper($dane["content_fileshowtype__sysname"])."_",
										"append" => 0,
										"rows" => 1,
										"options" => "",
										"xss_secured" => true
									));?>
									<?=sm_inputfield(array(
										"type"	=> "checkbox",
										"title"	=> "Wartość domyślna",
										"help"	=> "będzie się automatycznie ustawiać na nowo dodanych plikach",
										"id"	=> "danecontent_fileshowtypeitem_content_fileshowtypeitem__default",
										"name"	=> "danecontent_fileshowtypeitem[content_fileshowtypeitem__default]",
										"value"	=> $danecontent_fileshowtypeitem["content_fileshowtypeitem__default"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => "",
										"xss_secured" => true
									));?>
<?			if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
									<div class="btn-toolbar">
										<input type=hidden name="danecontent_fileshowtypeitem[content_fileshowtypeitem__id]" value="<?=$danecontent_fileshowtypeitem["content_fileshowtypeitem__id"]?>">
										<input type=hidden name="content_fileshowtypeitem__id" value="<?=$content_fileshowtypeitem__id?>">
										<input type=hidden name="danecontent_fileshowtypeitem[content_fileshowtype__id]" value="<?=$content_fileshowtype__id?>">
										<input type=hidden name="content_fileshowtype__id" value="<?=$content_fileshowtype__id?>">
<?				if ($danecontent_fileshowtypeitem["content_fileshowtypeitem__id"]) {?>
										<a class="btn btn-mini btn-info" id="action-item_edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
										<a class="btn btn-mini btn-danger" id="action-item_delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?				} else {?>
										<a class="btn btn-mini btn-info" id="action-item_add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?				}?>
									</div>
<?			} ?>
<?
		}
?>
<?
	}
?>
								</fieldset>
							</div>
						</div>
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
$('#action-item_edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[item_edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-item_delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[item_delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-item_add').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[item_add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>
<?
}
?>

<? include "_page_footer5.php" ?>
