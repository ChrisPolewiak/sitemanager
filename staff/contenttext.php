<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_text__id = $_REQUEST["content_text__id"];

if( isset($action["add"]) || isset($action["edit"]) )
{
	$dane=trimall($dane);
	if(!$dane["content_text__name"])
		$ERROR[]=__("core", "Podaj nazwę wewnętrzną artykułu");

	if(!$dane["content_text__title"])
		$ERROR[]=__("core", "Podaj tytuł artykułu");
}

if(!is_array($ERROR))
{
	if ( isset($action["add"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_text__id = content_text_add($dane);
	}

	elseif ( isset($action["edit"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$tmp = content_text_dane($dane["content_text__id"]);
		$content_text__id = content_text_edit($dane);
	}

	elseif ( isset($action["delete"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_text_delete($content_text__id);
		unset($content_text__id);
	}
}
else
	$content_text__id = $content_text__id ? $content_text__id : "0";

if( $content_text__id )
	$dane = content_text_dane( $content_text__id );

if(isset($action["clone"]))
{
	$content_text__id=0;
	$dane["content_text__id"]=0;
}

include "_page_header5.php";

if (!$content_text__id && $content_text__id!="0")
{
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_text",
		"function_fetch" => "content_text_fetch_all_new()",
		"mainkey" => "content_text__id",
		"columns" => array(
			array( "title"=>__("core", "Tytuł artykułu"), "width"=>"250", "value"=>"%%{content_text__title}%%", "order"=>1 ),
			array( "title"=>__("core", "Nazwa wewnętrzna"), "width"=>"100%", "value"=>"%%{content_text__name}%%", "order"=>1 ),
			array( "title"=>__("core", "Język"), "width"=>"30", "value"=>"%%{content_text__lang}%%", "order"=>1 ),
			array( "title"=>__("core", "Strona"), "width"=>"150", "value"=>"%%{content_page__name}%%</div>", "order"=>1 ),
			array( "title"=>__("core", "Sekcja"), "width"=>"150", "value"=>"%%{content_section__sysname}%%", "order"=>1 ),
		),
		"row_per_page_default" => 100,
	);
	include "_datatable_list5.php";
}
else
{
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_text__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<div class="row-fluid">
							<div class="span8">
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Tytuł artykułu",
										"help"=>"wyświetlany na stronie",
										"id"=>"dane_content_text__title",
										"name"=>"dane[content_text__title]",
										"value"=>$dane["content_text__title"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
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
												"type"=>"textarea",
												"title"=>"",
												"help"=>"",
												"id"=>"dane_content_text__lead",
												"name"=>"dane[content_text__lead]",
												"value"=>$dane["content_text__lead"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>5,
												"options"=>"",
												"xss_secured"=>false
											));?>
										</div>
										<div id="tabs-html" class="tab-pane active">
<?
	$sm_input_htmleditor["height"] = 300;
?>
											<?=sm_inputfield(array(
												"type"=>"htmleditor",
												"title"=>"",
												"help"=>"",
												"id"=>"dane_content_text__body",
												"name"=>"dane[content_text__body]",
												"value"=>$dane["content_text__body"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>5,
												"options"=>"",
												"xss_secured"=>false
											));?>
										</div>
									</div>
								</fieldset>

<?
	if (sm_core_content_user_accesscheck($access_type_id."_WRITE"))
	{
?>
								<input type=hidden name="dane[content_text__id]" value="<?=$dane["content_text__id"]?>">
								<input type=hidden name="content_text__id" value="<?=$dane["content_text__id"]?>">
								<input type=hidden name="content_text_table" value="<?=$dane["content_text_table"]?>">
								<input type=hidden name="content_text_id" value="<?=$dane["content_text_id"]?>">
								<div class="btn-toolbar">
<?
		if ($dane["content_text__id"])
		{
?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-info" id="action-clone"><i class="icon-tint icon-white"></i>&nbsp;<?=__("core", "KLONUJ")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?
		}
		else {
?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?
		}
?>
								</div>

							</div>
							<div class="span4">

								<div class="fieldset-title" id="ContentTaxtParams">
									<div><?=__("core", "Parametry")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<?=sm_inputfield(array(
										"type"=>"text",
										"title"=>"Nazwa wewnętrzna",
										"help"=>"",
										"id"=>"dane_content_text__name",
										"name"=>"dane[content_text__name]",
										"value"=>$dane["content_text__name"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>"",
										"xss_secured"=>true
									));?>
									<div class="row-fluid">
										<div class="span6">
<?
		$inputfield_options=array();
		for($i=1;$i<10;$i++)
			$inputfield_options[ $i ]=$i;
?>
											<?=sm_inputfield(array(
												"type"=>"select",
												"title"=>"Kolejność",
												"help"=>"wyświetlania się artykułów",
												"id"=>"dane_content_text__order",
												"name"=>"dane[content_text__order]",
												"value"=>$dane["content_text__order"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>1,
												"options"=>$inputfield_options,
												"xss_secured"=>true
											));?>
										</div>
										<div class="span6">
<?
		$inputfield_options=array();
		$inputfield_options[""]="dowolny";
		foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v)
			$inputfield_options[ $k ]=$v;
?>
											<?=sm_inputfield(array(
												"type"=>"select",
												"title"=>"Język",
												"help"=>"",
												"id"=>"dane_content_text__lang",
												"name"=>"dane[content_text__lang]",
												"value"=>$dane["content_text__lang"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>1,
												"options"=>$inputfield_options,
												"xss_secured"=>true
											));?>
										</div>
									</div>
<?
		$inputfield_options=array();
		$inputfield_options[]="Brak powiązania";
		foreach($CONTENT_PAGE_LONGNAME AS $k=>$v)
		{
			$prefix = substr($v,0,2);
			$v = preg_replace( "/(.+?)@@@/is", " / .. ", $v);
			$num = $k; if($k<10){ $num = "0".$num; } if($k<100){ $num = "0".$num; }
			$prefix = preg_replace( "/(.+?)@@@([^@]+)@*.*/", "\\1", $CONTENT_PAGE_LONGNAME[$k]);
			$v = $prefix." : ".$v;
			$inputfield_options[ $k ] = $v;
		}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>"Strona",
										"help"=>"do której strony przypiać ten artykuł",
										"id"=>"dane_content_page__id",
										"name"=>"dane[content_page__id]",
										"value"=>$dane["content_page__id"],
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
		$inputfield_options[]="Brak powiązania";
		if($result = content_section_fetch_all())
		{
			while($row=$result->fetch(PDO::FETCH_ASSOC))
				$inputfield_options[ $row["content_section__id"] ] = $row["content_section__name"];
		}
?>
									<?=sm_inputfield(array(
										"type"=>"select",
										"title"=>"Sekcja",
										"help"=>"do której strony przypiać ten artykuł",
										"id"=>"dane_content_section__id",
										"name"=>"dane[content_section__id]",
										"value"=>$dane["content_section__id"],
										"size"=>"block-level",
										"disabled"=>0,
										"validation"=>0,
										"prepend"=>0,
										"append"=>0,
										"rows"=>1,
										"options"=>$inputfield_options,
										"xss_secured"=>true
									));?>
								</fieldset>

<?
		$__table    = "content_text";
		$__tableid  = $content_text__id;
		require "__contenttags.php";
?>

<?
		if ($content_text__id)
		{
			$__table    = "content_text";
			$__tableid  = $content_text__id;
			include "__contentfile_attach.php";
		}
?>
							</div>
						</div>
<?
	}
?>
<script>
$('#action-clone').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[clone]" value=1>');
	$('#sm-form').submit();
});
$('#action-edit').click(function() {
	var data = CKEDITOR.instances.dane_content_text__body.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_text__body]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-add').click(function() {
	var data = CKEDITOR.instances.dane_content_text__body.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_text__body]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
?>

<? include "_page_footer5.php"; ?>