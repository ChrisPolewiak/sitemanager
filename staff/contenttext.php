<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_text__id = $_REQUEST["content_text__id"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if(!$dane["content_text__name"]){
		$ERROR[]=__("core", "Podaj nazwę wewnętrzną artykułu");
	}
	if(!$dane["content_text__title"]){
		$ERROR[]=__("core", "Podaj tytuł artykułu");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_text__id = content_text_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$tmp = content_text_dane($dane["content_text__id"]);
		$content_text__id = content_text_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_text_delete($content_text__id);
		unset($content_text__id);
	}
}
else {
	$content_text__id = $content_text__id ? $content_text__id : "0";
}

if( $content_text__id ) {
	$dane = content_text_dane( $content_text__id );
}

if(isset($action["clone"])){
	$content_text__id=0;
	$dane["content_text__id"]=0;
}

include "_page_header5.php";

if (!$content_text__id && $content_text__id!="0") {
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
else {
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
									<?=sm_inputfield( "text", "Tytuł artykułu", "wyświetlany na stronie", "dane_content_text__title", "dane[content_text__title]", $dane["content_text__title"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
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
											<?=sm_inputfield( "textarea", "", "", "dane_content_text__lead", "dane[content_text__lead]", $dane["content_text__lead"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=5, "", $xss_secured=0);?>
										</div>
										<div id="tabs-html" class="tab-pane active">
<?
	$sm_input_htmleditor["height"] = 300;
?>
											<?=sm_inputfield( "htmleditor", "", "", "dane_content_text__body", "dane[content_text__body]", $dane["content_text__body"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=5, "", $xss_secured=0);?>
										</div>
									</div>
								</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<input type=hidden name="dane[content_text__id]" value="<?=$dane["content_text__id"]?>">
								<input type=hidden name="content_text__id" value="<?=$dane["content_text__id"]?>">
								<input type=hidden name="content_text_table" value="<?=$dane["content_text_table"]?>">
								<input type=hidden name="content_text_id" value="<?=$dane["content_text_id"]?>">
								<div class="btn-toolbar">
<?		if ($dane["content_text__id"]) {?>
									<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
									<a class="btn btn-normal btn-info" id="action-clone"><i class="icon-tint icon-white"></i>&nbsp;<?=__("core", "KLONUJ")?></a>
									<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
									<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
								</div>

							</div>
							<div class="span4">

								<div class="fieldset-title" id="ContentTaxtParams">
									<div><?=__("core", "Parametry")?></div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<?=sm_inputfield( "text", "Nazwa wewnętrzna", "", "dane_content_text__name", "dane[content_text__name]", $dane["content_text__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<div class="row-fluid">
										<div class="span6">
<?
	$inputfield_options=array();
	for($i=1;$i<10;$i++) {
		$inputfield_options[ $i ]=$i;
	}
?>
											<?=sm_inputfield( "select", "Kolejność", "wyświetlania się artykułów", "dane_content_text__order", "dane[content_text__order]", $dane["content_text__order"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
										</div>
										<div class="span6">
<?
	$inputfield_options=array();
	foreach($SITE_LANG AS $k=>$v) {
		$inputfield_options[ $k ]=$v;
	}
?>
											<?=sm_inputfield( "select", "Język", "", "dane_content_text__lang", "dane[content_text__lang]", $dane["content_text__lang"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
										</div>
									</div>
<?
	$inputfield_options=array();
	$inputfield_options[]="Brak powiązania";
	foreach($CONTENT_PAGE_LONGNAME AS $k=>$v) {
		$prefix = substr($v,0,2);
		$v = preg_replace( "/(.+?)@@@/is", " / .. ", $v);
		$num = $k; if($k<10){ $num = "0".$num; } if($k<100){ $num = "0".$num; }
		$prefix = preg_replace( "/(.+?)@@@([^@]+)@*.*/", "\\1", $CONTENT_PAGE_LONGNAME[$k]);
		$v = $prefix." : ".$v;
		$inputfield_options[ $k ] = $v;
	}
?>
									<?=sm_inputfield( "select", "Strona", "do której strony przypiać ten artykuł", "dane_content_page__id", "dane[content_page__id]", $dane["content_page__id"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
<?
	$inputfield_options=array();
	$inputfield_options[]="Brak powiązania";
	if($result = content_section_fetch_all()) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$inputfield_options[ $row["content_section__id"] ] = $row["content_section__name"];
		}
	}
?>
									<?=sm_inputfield( "select", "Sekcja", "do której sekcji przypiać ten artykuł", "dane_content_section__id", "dane[content_section__id]", $dane["content_section__id"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
								</fieldset>

<?
	$__table    = "content_text";
	$__tableid  = $content_text__id;
	require "__contenttags.php";
?>

<?
	if ($content_text__id) {
		$__table    = "content_text";
		$__tableid  = $content_text__id;
		include "__contentfile_attach.php";
	}
?>
							</div>
						</div>
<?	} ?>
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
