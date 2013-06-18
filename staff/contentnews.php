<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_news__id = $_REQUEST["content_news__id"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if(!$dane["content_news__title"]){
		$ERROR[] = __("core", "Podaj tytuł wiadomości");
	}
	if(!$dane["content_news__datetime"]){
		$ERROR[] = __("core", "Podaj datę wiadomości");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_news__id = content_news_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$tmp = content_news_dane($dane["content_news__id"]);
		$content_news__id = content_news_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_news_delete($content_news__id);
		content_news2content_newsgroup_delete_by_content_news($content_news__id);
		content_tags_delete_by_id( $content_news__id, "content_news" );
		unset($content_news__id);
	}

	if ( isset($action["add"]) || isset($action["edit"]) ) {
		// groups
		if (is_array($dane_content_newsgroup)) {
			content_news2content_newsgroup_delete_by_content_news($content_news__id);
			foreach($dane_content_newsgroup AS $k=>$v){
				content_news2content_newsgroup_edit( $content_news__id, $k );
			}
		}
	}
}
else {
	$content_news__id = $content_news__id ? $content_news__id : "0";
}

if(isset($action["clone"])){
	$content_news__id=0;
	$dane["content_news__id"]=0;
}

if( $content_news__id ) {
	$dane = content_news_dane( $content_news__id );
	if($result = content_news2content_newsgroup_fetch_by_content_news( $content_news__id )){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$dane_content_newsgroup[$row["content_newsgroup__id"]]=1;
		}
	}
}

$menu_content_submenu = array();
if($result = content_newsgroup_fetch_all()){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		$menu_content_submenu[ $row["content_newsgroup__id"] ] = $row["content_newsgroup__name"];
	}
}

include "_page_header5.php";

if (!$content_news__id && $content_news__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_news",
		"mainkey" => "content_news__id",
		"columns" => array(
			array( "title"=>__("core", "Tytuł wiadomości"), "width"=>"100%", "value"=>"%%{content_news__title}%%", "order"=>1, ),
			array( "title"=>__("core", "Język"), "align"=>"left", "value"=>"%%{content_news__lang}%%", "order"=>1, ),
			array( "title"=>__("core", "Data publ."), "align"=>"left", "value"=>"%%{content_news__datetime}%%", "order"=>1, ),
			array( "title"=>__("core", "Kategoria"), "width"=>"150", "value"=>"%%{content_newsgroup__name}%%", "order"=>1, ),
			array( "title"=>__("core", "Status"), "align"=>"center", "value"=>"%%{content_news__published}%%", "order"=>1, 
				"valuesmatch"=>array( 1=>"<div class=green>".__("core", "aktywny")."</div>",0=>"<div class=gray>".__("core", "nieaktywny")."</div>" ),
			),
		),
		"row_per_page_default" => 100,
	);

	if ( isset($_GET["subidv"]) ) {
		$params["function_fetch"] = "content_news2content_newsgroup_fetch_all_group( '".$_GET["subidv"]."' )";
	}
	else {
		$params["function_fetch"] = "content_news2content_newsgroup_fetch_all_group()";
	}
	include "_datatable_list5.php";
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_news__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="row-fluid">
							<div class="span8">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"	=> "text",
										"title"	=> "Tytuł wiadomości",
										"help"	=> "wyświetlany na stronie",
										"id"	=> "dane_content_news__title",
										"name"	=> "dane[content_news__title]",
										"value"	=> $dane["content_news__title"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => "",
										"xss_secured" => true
									));?>
<script>
$('#tabs').ready(function() {
	$('#tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
</script>

									<ul class="nav nav-tabs" id="tabs">
										<li><a href="#tabs-text"><?=__("core", "Treść w formacie tekstowym")?></a></li>
										<li class="active"><a href="#tabs-html"><?=__("core", "Treść w formacie HTML")?></a></li>
									</ul>

									<div class="tab-content">
										<div id="tabs-text" class="tab-pane">
											<?=sm_inputfield(array(
												"type"	=> "textarea",
												"title"	=> "",
												"help"	=> "",
												"id"	=> "dane_content_news__lead",
												"name"	=> "dane[content_news__lead]",
												"value"	=> $dane["content_news__lead"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 5,
												"options" => "",
												"xss_secured" => false
											));?>
										</div>
										<div id="tabs-html" class="tab-pane active">
<?
	$sm_input_htmleditor["height"] = 300;
?>
											<?=sm_inputfield(array(
												"type"	=> "htmleditor",
												"title"	=> "",
												"help"	=> "",
												"id"	=> "dane_content_news__body",
												"name"	=> "dane[content_news__body]",
												"value"	=> $dane["content_news__body"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 5,
												"options" => "",
												"xss_secured" => false
											));?>
										</div>
									</div>
								</fieldset>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<input type=hidden name="dane[content_news__id]" value="<?=$dane["content_news__id"]?>">
								<input type=hidden name="content_news__id" value="<?=$dane["content_news__id"]?>">
								<div class="btn-toolbar">
<?	if ($dane["content_news__id"]) {?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-info" id="action-clone"><i class="icon-tint icon-white"></i>&nbsp;<?=__("core", "KLONUJ")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?	} else {?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?	}?>
								</div>
<? } ?>

							</div>
							<div class="span4">
								<div class="fieldset-title" id="ContentNewsParams">
									<div><?=__("core", "Parametry wiadomości")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
<?
	$dane["content_news__datetime"] = $dane["content_news__datetime"] ? $dane["content_news__datetime"] : date("Y-m-d");
?>
									<?=sm_inputfield(array(
										"type"	=> "calendar",
										"title"	=> "Data publikacji",
										"help"	=> "",
										"id"	=> "dane_content_news__datetime",
										"name"	=> "dane[content_news__datetime]",
										"value"	=> $dane["content_news__datetime"],
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
	$inputfield_options=array();
	$inputfield_options[""]="dowolny";
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		$inputfield_options[ $k ]=$v;
	}
?>
									<?=sm_inputfield(array(
										"type"	=> "select",
										"title"	=> "Język",
										"help"	=> "",
										"id"	=> "dane_content_news__lang",
										"name"	=> "dane[content_news__lang]",
										"value"	=> $dane["content_news__lang"],
										"size"	=> "block-level",
										"disabled" => 0,
										"validation" => 0,
										"prepend" => 0,
										"append" => 0,
										"rows" => 1,
										"options" => $inputfield_options,
										"xss_secured" => true
									));?>
									<?=sm_inputfield(array(
										"type"	=> "checkbox",
										"title"	=> "Aktywna",
										"help"	=> "Czy wiadomość jest widoczna",
										"id"	=> "dane_content_news__published",
										"name"	=> "dane[content_news__published]",
										"value"	=> $dane["content_news__published"],
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
<?
	$__table    = "content_news";
	$__id_table = $content_news__id;
	require "__contenttags.php";
?>
								<div class="fieldset-title" id="ContentNewsCategory">
									<div><?=__("core", "Kategorie wiadomości")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<table class="table table-striped">
										<thead>
											<tr>
												<th></th>
												<th width="100%"><?=__("core", "Nazwa kategorii")?></th>
											</tr>
										</thead>
										<tbody>
<?
	if($result=content_newsgroup_fetch_all()){
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$class_selected = ($dane_content_newsgroup[$row["content_newsgroup__id"]] ? "success" : "");
?>
											<tr valign=top class="<?=$class_selected?>">
												<td>
													<?=sm_inputfield(array(
														"type"	=> "checkbox",
														"title"	=> "",
														"help"	=> "",
														"id"	=> "dane_content_newsgroup_".$row["content_newsgroup__id"],
														"name"	=> "dane_content_newsgroup[".$row["content_newsgroup__id"]."]",
														"value"	=> $dane_content_newsgroup[$row["content_newsgroup__id"]],
														"size"	=> "block-level",
														"disabled" => 0,
														"validation" => 0,
														"prepend" => 0,
														"append" => 0,
														"rows" => 1,
														"options" => "",
														"xss_secured" => true
													));?>
												</td>
												<td><label for="dane_content_newsgroup_<?=$row["content_newsgroup__id"]?>"><?=$row["content_newsgroup__name"]?></label</td>
											</tr>
<?
		}
	}
?>
										</tbody>
									</table>
								</fieldset>
<?
	if ($content_news__id) {
		$__table   = "content_news";
		$__tableid = $content_news__id;
		include "__contentfile_attach.php";
	}
?>
							</div>
						</div>
<script>
$('#action-clone').click(function() {
	var data = CKEDITOR.instances.dane_content_news__body.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_news__body]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[clone]" value=1>');
	$('#sm-form').submit();
});
$('#action-edit').click(function() {
	var data = CKEDITOR.instances.dane_content_news__body.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_news__body]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-add').click(function() {
	var data = CKEDITOR.instances.dane_content_news__body.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_news__body]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
?>

<? include "_page_footer5.php"; ?>
