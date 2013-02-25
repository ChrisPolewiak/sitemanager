<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_mailtemplate__id = $_REQUEST["content_mailtemplate__id"];
$content_user__id = $_REQUEST["content_user__id"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if(!$dane["content_mailtemplate__sysname"]){
		$ERROR[] = __("core", "Podaj nazwę systemową dla szablonu");
	}
	if(! preg_match("/^[\d\w\-\_]+$/", $dane["content_mailtemplate__sysname"])) {
		$ERROR[] = __("core", "Nieprawidłowe znaki w nazwie systemowej");
	}
	if(!$dane["content_mailtemplate__name"]){
		$ERROR[] = __("core", "Podaj nazwę szablonu");
	}
}
if( isset($action["mail2user_add"]) ){
	if(!$content_user__id) {
		$ERROR[] = __("core", "Wybierz użytkownika");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_mailtemplate__id = content_mailtemplate_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$tmp = content_mailtemplate_dane($dane["content_mailtemplate__id"]);
		$content_mailtemplate__id = content_mailtemplate_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_mailtemplate_delete($content_mailtemplate__id);
		unset($content_mailtemplate__id);
	}
	elseif ( isset($action["mail2user_add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_mailtemplate2content_user_edit( array("content_mailtemplate__id"=>$content_mailtemplate__id, "content_user__id"=>$content_user__id) );
	}
	elseif ( isset($action["mail2user_del"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_mailtemplate2content_user_delete( $content_mailtemplate__id, $content_user__id );
	}
}
else {
	$content_mailtemplate__id = $content_mailtemplate__id ? $content_mailtemplate__id : "0";
}

if(isset($action["clone"])){
	$content_mailtemplate__id=0;
	$dane["content_mailtemplate__id"]=0;
}

if( $content_mailtemplate__id > 0 ) {
	$dane = content_mailtemplate_dane( $content_mailtemplate__id );
	if( isset($action["duplicate"] ) ) {
		$content_mailtemplate__id = "0";
		$dane["content_mailtemplate__id"] = "0";
	}
}

include "_page_header5.php";

if (!$content_mailtemplate__id && $content_mailtemplate__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_mailtemplate",
		"function_fetch" => "content_mailtemplate_fetch_all()",
		"mainkey" => "content_mailtemplate__id",
		"columns" => array(
			array( "title"=>__("core", "Nazwa szablonu"), "width"=>"250", "value"=>"%%{content_mailtemplate__name}%%", "order"=>1 ),
			array( "title"=>__("core", "Tytuł treści maila"), "width"=>"100%", "value"=>"%%{content_mailtemplate__subject}%%", "order"=>1 ),
			array( "title"=>__("core", "Nazwa wewnętrzna"), "width"=>"200", "value"=>"%%{content_mailtemplate__sysname}%%", "order"=>1 ),
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
							<a class="btn btn-small btn-info" href="?content_mailtemplate__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<div class="row-fluid">
							<div class="span8">
								<fieldset class="no-legend">
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
											<?=sm_inputfield( "textarea", "", "", "dane_content_mailtemplate__textbody", "dane[content_mailtemplate__textbody]", $dane["content_mailtemplate__textbody"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=10);?>
										</div>
										<div id="tabs-html" class="tab-pane active">
<?
	$sm_input_htmleditor["height"] = 300;
?>
											<?=sm_inputfield( "htmleditor", "", "", "dane_content_mailtemplate__htmlbody", "dane[content_mailtemplate__htmlbody]", $dane["content_mailtemplate__htmlbody"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=5, "", $xss_secured=0);?>
										</div>
									</div>
								</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_mailtemplate__id]" value="<?=$dane["content_mailtemplate__id"]?>">
							<input type=hidden name="content_mailtemplate__id" value="<?=$dane["content_mailtemplate__id"]?>">
							<input type=hidden name="content_mailtemplate__table" value="<?=$dane["content_mailtemplate__table"]?>">
							<input type=hidden name="content_mailtemplate__tableid" value="<?=$dane["content_mailtemplate__tableid"]?>">
<?		if ($dane["content_mailtemplate__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-info" id="action-clone"><i class="icon-tint icon-white"></i>&nbsp;<?=__("core", "KLONUJ")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?		}?>
						</div>
<?	}?>

							</div>
							<div class="span4">

								<div class="fieldset-title" id="ContentMailTemplateParams">
									<div>Parametry szablonu</div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<?=sm_inputfield( "text", "Nazwa szablonu", "", "dane_content_mailtemplate__name", "dane[content_mailtemplate__name]", $dane["content_mailtemplate__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Nazwa wewnętrzna", "używana w kodzie", "dane_content_mailtemplate__sysname", "dane[content_mailtemplate__sysname]", $dane["content_mailtemplate__sysname"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Tytuł wiadomości e-mail", "", "dane_content_mailtemplate__subject", "dane[content_mailtemplate__subject]", $dane["content_mailtemplate__subject"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Nazwa nadawcy", "", "dane_content_mailtemplate_sender_name", "dane[content_mailtemplate__sender_name]", $dane["content_mailtemplate__sender_name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
									<?=sm_inputfield( "text", "Adres E-mail nadawcy", "", "dane_content_mailtemplate_sender_email", "dane[content_mailtemplate__sender_email]", $dane["content_mailtemplate__sender_email"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
								</fieldset>
<?
	if ($content_mailtemplate__id) {
		// file
		$__activatetab_function = "tab_select('tab_attachments', 'tab_body')";
		$__table    = "content_mailtemplate";
		$__id_table = $content_mailtemplate__id;
		include "__contentfile_attach.php";
	}
?>
								<div class="fieldset-title" id="ContentMailTemplateRecipients">
									<div>Adresaci wiadomości</div><i class="icon-minus"></i>
								</div>
								<fieldset class="no-legend">
									<table class="table">
										<thead>
											<tr>
												<th><?=__("core", "Użytkownik/E-mail")?></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
<?
	if($result=content_mailtemplate2content_user_fetch_by_content_mailtemplate( $content_mailtemplate__id )){
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
?>
											<tr>
												<td>
													<?=$row["content_user__surname"]?> <?=$row["content_user__firstname"]?><br>
													<?=$row["content_user__email"]?>
												</td>
												<td><a href="?content_mailtemplate__id=<?=$content_mailtemplate__id?>&content_user__id=<?=$row["content_user__id"]?>&action[mail2user_del]=1"><i class="icon-delete"></i></a></td>
											</tr>
<?
		}
	}
?>
										</tbody>
										<tfoot>
											<tr>
												<td>
													<?=sm_inputfield( "text", "Wyszukaj użytkownika", "", "mail2content_user_name", "mail2content_user[name]", $mail2content_user["name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
													<input type="hidden" name="mail2content_user[id]" value="" id="mail2content_userid">
<script>
$('#mail2content_user_name').autocomplete({
	source: function( request, response ) {
		$.ajax({
			url: "/admin/search-ajax.php?resulttype=json&object=content_user",
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
		$('#mail2content_user_name').val( ui.item.value );
		$('#mail2content_userid').val( ui.item.id );
	}
});	
</script>
												</td>
												<td>
													<a href="#" OnClick="window.location='?content_mailtemplate__id=<?=$content_mailtemplate__id?>&content_user__id=' + document.getElementById('Mail2content_userId').value + '&action[mail2user_add]=1'"><?=__("CORE", "BUTTON__SAVE")?></a>
												</td>
											</tr>
										</tfoot>
        								</table>
        							</fieldset>

							</div>
						</div>

<script>
$('#action-clone').click(function() {
	var data = CKEDITOR.instances.dane_content_mailtemplate__htmlbody.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_mailtemplate__htmlbody]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[clone]" value=1>');
	$('#sm-form').submit();
});
$('#action-edit').click(function() {
	var data = CKEDITOR.instances.dane_content_mailtemplate__htmlbody.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_mailtemplate__htmlbody]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
$('#action-delete').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[delete]" value=1>');
	$('#sm-form').submit();
});
$('#action-add').click(function() {
	var data = CKEDITOR.instances.dane_content_mailtemplate__htmlbody.getData();
	$('#sm-form').append('<textarea style="display:none" name="dane[content_mailtemplate__htmlbody]">'+data+'</textarea>');
	$('#sm-form').append('<input type="hidden" name="action[add]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<?
}
?>

<? include "_page_footer5.php"; ?>
