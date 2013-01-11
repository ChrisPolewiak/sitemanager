<?
sm_core_content_user_accesscheck($access_type_id."_PLUS",1);

$mode = $_GET["mode"];
if( $_GET["CKEditor"] ) {
	$mode = "ckeditor";
}
if( $_GET["CKEditorFuncNum"]) {
	$CKEditorFuncNum_callback = $_GET["CKEditorFuncNum"];
}

if(isset($action["set_viewtype"])) {
	switch( $action["set_viewtype"] ) {
		case "thumbs": case "list": case "form":
			$sm_viewtype = $action["set_viewtype"];
			break;
	}
}

if($_REQUEST["ajax"]) {
	switch ($_REQUEST["action"]) {
		case "fileinfo":
			if ($ajax_dane = content_file_dane( $_REQUEST["id"])) {
				unset($ajax_dane["content_file__filedata"]);
				echo json_encode($ajax_dane);
			}
			break;
		case "loaddata":
			switch($_GET["sm_viewtype"]) {
				case "table":
					require "_contentfile_popup_table.php";
					break;
				case "thumbs":
					require "_contentfile_popup_thumbs.php";
					break;
				default:
					echo "nie znany typ widoku";
					break;
			}
			break;
		case "save-multi":
			if(sizeof($_POST["filesselected"])>0) {
				foreach($_POST["filesselected"] AS $k=>$content_file__id) {
					#echo " $k=>$content_file__id \n";
					content_fileassoc_edit( $content_file__id, $_POST["content_file__tableid"], $_POST["content_file__table"], 0, 0 );
				}
			}
			else {
				echo "Nie zaznaczono żadnych plików";
			}
			break;
	}
	exit;
}

$sm_viewtype = $sm_viewtype ? $sm_viewtype : "table";

include "_page_header_popup.php";

/*
switch($sm_viewtype) {

	#
	# View Type Table
	####################
	case "table":
		require "_contentfile_popup_table.php";
		break;

	#
	# View Type Thumbs
	####################
	case "thumbs":
		require "_contentfile_popup_thumbs.php";
		break;

	#
	# View Type Form
	####################
	case "form":

	break;
}
*/
?>
	<div style="float: left;width: 100%;">
		<div style="margin-left: 300px;" class="sm-dataview">
		</div>
	</div>
	<div style="float: left;width: 280px;margin-left: -100%;">

		<div class="fieldset-title">
			<div>Informacje o pliku</div>
		</div>
		<fieldset class="no-legend sm-fileinfo">
			<input type="hidden" name="content_file__id" id="content_file__id" value="">
			<table class="table">
				<tbody>
					<tr>
						<td colspan=2 class="sm-filethumbnail"><?=$dane["content_file__filename"]?></td>
					</tr>
					<tr>
						<td>Nazwa pliku</td>
						<td class="sm-filename"><?=$dane["content_file__filename"]?></td>
					</tr>
				</tbody>
			</table>
<?
if($mode=="ckeditor") {
?>
			<div class="btn-toolbar">
				<a class="btn btn-mini btn-info" href="#" id="action-select-file">Wybierz</a>
			</div>
<?
}
?>
		</fieldset>
<?
if($mode=="multi") {
?>
		<form action="" id="sm-fileselected">

			<div class="fieldset-title">
				<div>Wybrane pliki</div>
			</div>
			<fieldset class="sm-fileselected no-legend">
				<div class="fileselected-wrap">
					<table class="table"></table>
				</div>
				<input type="hidden" name="content_file__tableid" value="<?=$_GET["content_file__tableid"]?>">
				<input type="hidden" name="content_file__table" value="<?=$_GET["content_file__table"]?>">
				<div class="btn-toolbar">
					<a class="btn btn-mini btn-info" href="#" id="action-select-files">Wybierz</a>
				</div>
			</fieldset>
		</form>	
<?
}
?>
	</div>

<script>
$().ready(function(){ 

	$.loaddata = function( sm_viewtype ) {
		url = '?ajax=1&action=loaddata&sm_viewtype='+sm_viewtype;
		$.ajax({
			url: url,
			success: function(data) {
				if(data) {
					$('.sm-dataview').html( data );
				}
			},
			error: function(data) {
				alert('error:'+data);
			}
		});
		$('.navbar-static-top ul.nav li').removeClass('active');
		$('.navbar-static-top ul.nav li#btn-action-viewtype-'+sm_viewtype).addClass('active');
		window.location.hash = 'sm_viewtype-'+sm_viewtype;
		$.cookie('smbrowser-viewtype', sm_viewtype);
	}

	switch(window.location.hash) {
		case 'sm_viewtype-table':
			$.loaddata( 'table' );
			break;
		case 'sm_viewtype-thumbs':
			$.loaddata( 'thumbs' );
			break;
		case 'sm_viewtype-form':
			$.loaddata( 'form' );
			break;
		default:
			if (sm_viewtype = $.cookie('smbrowser-viewtype') ) {
				$.loaddata( sm_viewtype );
			}
			else {
				$.loaddata( 'thumbs' );
			}
			break;
	}

	$('#action-viewtype-thumbs').unbind();
	$('#action-viewtype-thumbs').bind('click',function(){
		$.loaddata( 'thumbs' );
	});

	$('#action-viewtype-table').unbind();
	$('#action-viewtype-table').bind('click',function(){
		$.loaddata( 'table' );
	});

	$('#action-viewtype-form').unbind();
	$('#action-viewtype-form').bind('click',function(){
		$.loaddata( 'form' );
	});

	$('#action-select-file').unbind();
	$('#action-select-file').bind('click',function(){
		fileid = $('.sm-fileinfo #content_file__id').val();

		window.opener.CKEDITOR.tools.callFunction(<?=$CKEditorFuncNum_callback?>,'/cacheimg?id='+fileid)
		window.close();
	});

	$('#action-select-files').unbind();
	$('#action-select-files').bind('click',function(){
		data = $('#sm-fileselected').serialize();
		url = '?ajax=1&action=save-multi';
		$.ajax({
			type: 'POST',
			data: data,
			url: url,
			success: function(data) {
				if(data) {
					alert(data);
				}
				else {
					window.opener.location.reload();
					window.close();
				}
			},
			error: function(data) {
				alert('error:'+data);
			}
		});
	});

	$('.sm-fileselected .action-selected-remove').die();
	$('.sm-fileselected .action-selected-remove').live('click',function() {
		el = $(this);
		id = el.parent().parent().attr('id');
		$('.sm-fileselected #'+id+'').remove();
	});

});
</script>
<style>
.sm-view-thumbs {
	height:660px;
	overflow-y:scroll;
}
.sm-view-item {
	float:left;
	width:150px;
	overflow:hidden;
	height:150px;
	border:1px solid #ddd;
	padding:5px;
	margin: 5px;
}
.sm-view-item.success {
	background-color: #dff0d8;
}
.sm-view-item.success img {
	opacity: 0.5;
}
.sm-fileinfo {
	height: 352px;
}
.sm-fileselected .fileselected-wrap {
	height: 200px;
	overflow-y: scroll;
}
</style>
<?
include "_footer.php";
?>