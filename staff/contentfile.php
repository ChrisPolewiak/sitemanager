<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_file__id = $_REQUEST["content_file__id"];
$content_page__id = $_REQUEST["content_page__id"];

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_file__id = content_file_add($dane);
		header("Location: $page?content_file__id=$content_file__id");
		exit;
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_file__id = content_file_edit($dane);
		header("Location: $page?content_file__id=$content_file__id");
		exit;
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_file_delete( $content_file__id );
		content_cache_delete( "content_file", $content_file__id );
		unset($content_file__id);
		header("Location: $page");
		exit;
	}
	elseif ( isset($action["upload"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_file_import( $dane );
		header("Location: $page");
		exit;
	}
}

if( $content_file__id ) {
	$dane = content_file_dane( $content_file__id );
}
if( $dane["content_user__id"] ) {
	$content_user = content_user_dane( $dane["content_user__id"] );
}
if( $content_page__id ) {
	$dane_content_filecontent_page = content_page_get( $content_page__id );
}
if( $content_file__id && $content_page__id ) {
	$dane_content_filecontent_file2page = content_file2content_page_get( $content_file__id, $content_page__id );
}

include "_page_header5.php";

if (!$content_file__id && $content_file__id!="0" && !isset($multiadd_form)) {
	
	foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
		$v = preg_replace( "/(@@@)/is", " / ", $v);
		$_content_category[ $k ] = $v;
	}

	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_file",
		"function_fetch" => "content_file_fetch_all()",
		"mainkey" => "content_file__id",
		"title" => __("core", "Lista plików"),
		"columns" => array(
			array( "title"=>__("core", "Foto"), "width"=>"25", "value"=>"%%[image]{content_file__id}%%", "order"=>1 ),
			array( "title"=>__("core", "Nazwa pliku"), "width"=>"200", "value"=>"%%{content_file__filename}%%", "order"=>1 ),
			array( "title"=>__("core", "Kategoria"), "width"=>"200", "value"=>"%%{content_category__id}%%", "order"=>1, "valuesmatch"=>$_content_category, ),
			array( "title"=>__("core", "Rozmiar"), "width"=>"100", "align"=>"right", "value"=>"%%{content_file__filesize} b%%" ),
			array( "title"=>__("core", "Data dodania"), "width"=>"100", "align"=>"right", "value"=>"%%[date]{record_create_date}%%" ),
			array( "title"=>__("core", "Typ pliku"), "width"=>"100", "align"=>"center", "value"=>"%%{content_file__filetype}%%", "order"=>1 ),
                ),
		"row_per_page_default" => 100,
        );

	$datatable_btn_add = array();
	$datatable_btn_add[] = array(
		"color-class"=>"info",
		"url"=>"?multiadd_form=1",
		"icon"=>"plus",
		"title"=>"Import plików",
	);

	include("_datatable_list5.php");
}
elseif( isset($multiadd_form)) {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_mailtemplate__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
							<?=sm_inputfield( "file-multi", "Pliki", "", "dane_upload", "upload[]", "", "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10);?>
<?
	$inputfield_options=array();
	$inputfield_options[]="wybierz";
	if(is_array($CONTENT_CATEGORY_LONGNAME)) {
		foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
			$v = preg_replace( "/(@@@)/is", " / ", $v);
			$inputfield_options[ $k ]= $v;
		}
	}
?>	
							<?=sm_inputfield( "select", "Kategoria", "", "dane_content_category__id", "dane[content_category__id]", $dane["content_category__id"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
						</fieldset>
<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<a class="btn btn-normal btn-info" id="action-upload"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
						</div>
<?	}?>
<script>
$('#action-upload').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[upload]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?content_mailtemplate__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
							<a class="btn btn-small btn-info" href="?multiadd_form=1"><i class="icon-plus icon-white"></i>&nbsp;<?=__("core", "Import plików")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<div class="row-fluid">
							<div class="span8">
								<fieldset class="no-legend">
									<?=sm_inputfield( "file", "Plik", "", "dane_upload", "upload", "", "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10);?>
<?
	$inputfield_options=array();
	$inputfield_options[]="wybierz";
	if(is_array($CONTENT_CATEGORY_LONGNAME)) {
		foreach($CONTENT_CATEGORY_LONGNAME AS $k=>$v) {
			$v = preg_replace( "/(@@@)/is", " / ", $v);
			$inputfield_options[ $k ]= $v;
		}
	}
?>	
									<?=sm_inputfield( "select", "Kategoria", "", "dane_content_category__id", "dane[content_category__id]", $dane["content_category__id"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
								</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type="hidden" name="dane[content_file__id]" value="<?=$dane["content_file__id"]?>">
							<input type="hidden" name="content_file__id" value="<?=$dane["content_file__id"]?>">
<?		if ($dane["content_file__id"]) { ?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
						</div>
<?	}?>

							</div>

							<div class="span4">

								<div class="fieldset-title">
									<div>Dane techniczne</div>
								</div>
								<fieldset class="sm-fileselected no-legend">
<?
	if ($content_file__id && preg_match("/^image/", $dane["content_file__filetype"])) {
?>
									<img src="/staff/__contentfile_image_resize.php?id=<?=$content_file__id?>&w=250" class="img-polaroid">
<?
	}
?>
									<table class="table">
										<tbody>
											<tr>
												<td>Nazwa pliku</td>
												<td><?=$dane["content_file__filename"]?></td>
											</tr>
											<tr>
												<td>Typ pliku</td>
												<td><?=$dane["content_file__filetype"]?></td>
											</tr>
											<tr>
												<td>Rozmiar</td>
												<td><?=number_format($dane["content_file__filesize"],0,","," ")?></td>
											</tr>
											<tr>
												<td>Właściciel</td>
												<td>
<?
	$content_user__name = (is_array($content_user) ? $content_user["content_user__surname"]." ".$content_user["content_user__firstname"] : "");
?>
													<?=sm_inputfield( "text", "", "", "dane_content_user", "dane[a]", $content_user__name, "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
													<input type="hidden" name="dane[content_user__id]" value="" id="dane_content_user__id">
<script>
$('#dane_content_user').autocomplete({
	source: function( request, response ) {
		$.ajax({
			url: "/<?=$SM_ADMIN_PANEL?>/search-ajax.php?resulttype=json&object=content_user",
			dataType: "json",
			data: {q: request.term},
			success: function(data) {
				if(data) {
					response($.map(data, function(item) {
						return {
							id: item.id,
							label: item.name
						};
					}));
				}
			}
		});
	},
	minLength: 1,
	select: function( event, ui ) {
		$('#dane_content_user').val( ui.item.value );
		$('#dane_content_user__id').val( ui.item.id );
	}
});	
</script>
												</td>
											</tr>
										</tbody>
									</table>
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
</script>
					</form>
<?}?>

<? include "_page_footer5.php"; ?>
