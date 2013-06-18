<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_newsgroup__id = $_REQUEST["content_newsgroup__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_newsgroup__name"]){
		$ERROR[]="Podaj nazwę kategorii";
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_newsgroup__id = content_newsgroup_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_newsgroup__id = content_newsgroup_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_newsgroup__id = content_newsgroup_delete($content_newsgroup__id);
		unset($dane);
	}
}
else {
	$content_newsgroup__id = $content_newsgroup__id ? $content_newsgroup__id : "0";
}

if( $content_newsgroup__id ) {
	$dane = content_newsgroup_dane( $content_newsgroup__id );
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_newsgroup__id && $content_newsgroup__id!="0") {
        $params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_newsgroup",
		"function_fetch" => "content_newsgroup_fetch_all()",
		"mainkey" => "content_newsgroup__id",
                "title" => "Kategorie wiadomości",
                "columns" => array(
                        array( "title"=>"Nazwa kategorii", "width"=>"100%", "value"=>"%%{content_newsgroup__name}%%", "order"=>1, ),
                        array( "title"=>"nazwa systemowa", "width"=>"200", "value"=>"%%{content_newsgroup__tag}%%", "order"=>1, ),
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
							<a class="btn btn-small btn-info" href="?content_newsgroup__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> "Nazwa kategorii",
								"help"	=> "",
								"id"	=> "dane_content_newsgroup__name",
								"name"	=> "dane[content_newsgroup__name]",
								"value"	=> $dane["content_newsgroup__name"],
								"size"	=> "block-level",
								"disabled" => 0,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => true
							));?>
							<?=sm_inputfield(array(
								"type"	=> "text",
								"title"	=> "Tag",
								"help"	=> "potrzebny w szablonach",
								"id"	=> "dane_content_newsgroup__tag",
								"name"	=> "dane[content_newsgroup__tag]",
								"value"	=> $dane["content_newsgroup__tag"],
								"size"	=> "block-level",
								"disabled" => 0,
								"validation" => 0,
								"prepend" => 0,
								"append" => 0,
								"rows" => 1,
								"options" => "",
								"xss_secured" => true
							));?>
						</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_newsgroup__id]" value="<?=$dane["content_newsgroup__id"]?>">
							<input type=hidden name="content_newsgroup__id" value="<?=$content_newsgroup__id?>">
<?		if ($dane["content_newsgroup__id"]) {?>
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
