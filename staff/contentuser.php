<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_user__id = $_REQUEST["content_user__id"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if( isset($action["add"]) && !$dane["content_user__password"]){
		$ERROR[]=__("core", "Podaj hasło");
	}
	if ($dane["content_user__username"] && $dane["content_user__password"] == $dane["content_user__username"]) {
		$ERROR[] = __("core", "Hasło musi być inne niż identyfikator");
	}
	if(isset($action["add"]) && content_user_get_by_username($dane["content_user__username"])){
		$ERROR[] = __("core", "Podany identyfikator jest już przypisany do innego użytkownika");
	}
	if(isset($action["edit"]) && $tmp=content_user_get_by_username($dane["content_user__username"])){
		if($tmp["content_user__id"] != $dane["content_user__id"]){
			$ERROR[] = __("core", "Podany identyfikator jest już przypisany do innego użytkownika");
		}
	}
	if(!$dane["content_user__email"]){
		$ERROR[]=__("core", "Podaj adres e-mail");
	}
	if(!$dane["content_user__username"]){
		$ERROR[]=__("core", "Podaj identyfikator");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_user__id = content_user_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_user__id = content_user_edit($dane);
		content_useracl_delete_content_user( $content_user__id );
		if($content_user__id && is_array($set)) {
			foreach($set AS $__content_access__id=>$null){
				content_useracl_add( $__content_access__id, $content_user__id, 1 );
			}
		}
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_user_disable($dane["content_user__id"]);
	}

	if( isset($action["add"]) || isset($action["edit"]) ){
		if (is_array($dane_content_usergroup)) {
			content_user2content_usergroup_delete_by_content_user($content_user__id);
			foreach($dane_content_usergroup AS $k=>$v){
				content_user2content_usergroup_edit( $content_user__id, $k );
			}
		}
	}
}
else {
	$content_user__id = $content_user__id ? $content_user__id : "0";
}

if( $content_user__id ) {
	$dane = content_user_dane( $content_user__id );
	if($result = content_user2content_usergroup_fetch_by_content_user( $content_user__id )){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$dane_content_usergroup[$row["content_usergroup__id"]]=1;
		}
	}
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_user__id && $content_user__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_user",
		"function_fetch" => "content_user_fetch_all()",
		"mainkey" => "content_user__id",
		"columns" => array(
			array( "title"=>__("core", "Nazwisko i imię"), "width"=>"100%", "value"=>"%%{content_user__surname} {content_user__firstname}%%", "order"=>1, ),
			array( "title"=>__("core", "Identyfikator"), "width"=>"100", "value"=>"%%{content_user__username}%%", "order"=>1, ),
			array( "title"=>__("core", "Adres e-mail"), "width"=>"150", "value"=>"%%{content_user__email}%%", ),
			array( "title"=>__("core", "Ostatni dostęp"), "width"=>"110", "value"=>"%%[date]{content_user__login_correct}%%", ),
			array( "title"=>__("core", "Status"), "align"=>"center", "value"=>"%%{content_user__status}%%", "order"=>1,
				"valuesmatch"=>array( 
		                    1=>"<div class=gray>".__("core", "Nowy - nieaktywny")."</div>",
		                    2=>"<div class=green>".__("core", "Aktywny")."</div>", 
		                    3=>"<div class=red>".__("core", "Zablokowany")."</div>"
                ),
			),

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
							<a class="btn btn-small btn-info" href="?content_user__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

<script>
$('#tabs').ready(function() {
	$('#tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
</script>

						<ul class="nav nav-tabs" id="tabs">
							<li class="active"><a href="#tabs-1"><?=__("core", "Dane osobowe")?></a></li>
							<li><a href="#tabs-2"><?=__("core", "Uprawnienia, Grupy, Status dostępu")?></a></li>
							<li><a href="#tabs-3"><?=__("core", "Uprawnienia Indywidualne")?></a></li>
							<li><a href="#tabs-4"><?=__("core", "Pola dodatkowe")?></a></li>
						</ul>

						<div class="tab-content">
							<div id="tabs-1" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
<?/* Dane osobowe */ ?>
										<fieldset class="no-legend">
											<div class="row-fluid">
												<div class="span6">
													<?=sm_inputfield( "text", "Nazwisko", "", "dane_content_user__surname", "dane[content_user__surname]", $dane["content_user__surname"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
												<div class="span6">
													<?=sm_inputfield( "text", "Imię", "", "dane_content_user__firstname", "dane[content_user__firstname]", $dane["content_user__firstname"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
											</div>

											<div class="row-fluid">
												<div class="span6">
													<?=sm_inputfield( "text", "Adres e-mail", "", "dane_content_user__email", "dane[content_user__email]", $dane["content_user__email"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
												<div class="span6">
													<?=sm_inputfield( "text", "Telefon", "", "dane_content_user_phone", "dane[content_user_phone]", $dane["content_user_phone"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
											</div>
											
											<?=sm_inputfield( "checkbox", "Czy adres ukryć przed innymi użytkownikami?", "", "dane_content_user_hide_email", "dane[content_user_hide_email]", $dane["content_user__email"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>

											<?=sm_inputfield( "text", "Firma", "", "dane_content_user_company", "dane[content_user_company]", $dane["content_user_company"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										
											<div class="row-fluid">
												<div class="span2">
													<?=sm_inputfield( "text", "Kod poczt.", "", "dane_content_user_postcode", "dane[content_user_postcode]", $dane["content_user_postcode"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
												<div class="span5">
													<?=sm_inputfield( "text", "Miasto", "", "dane_content_user_city", "dane[content_user_city]", $dane["content_user_city"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
												<div class="span5">
													<?=sm_inputfield( "text", "Państwo", "", "dane_content_user_country", "dane[content_user_country]", $dane["content_user_country"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
											</div>

											<?=sm_inputfield( "text", "Ulica", "", "dane_content_user_street", "dane[content_user_street]", $dane["content_user_street"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
											<?=sm_inputfield( "textarea", "Komentarz administratora", "", "dane_content_user_comment", "dane[content_user_comment]", $dane["content_user_comment"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=2);?>

										</fieldset>

									</div>
									<div class="span4">

<?/* Zgody */ ?>
										<div class="fieldset-title">
											<div>Zgody</div>
										</div>
										<fieldset class="no-legend">
											<?=sm_inputfield( "checkbox", "Potwierdził regulamin", "", "dane_content_user_confirm_regulation", "dane[content_user_confirm_regulation]", $dane["content_user_confirm_regulation"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
											<?=sm_inputfield( "checkbox", "Zgoda na przetwarzanie danych osobowych", "", "dane_content_user_confirm_userdata", "dane[content_user_confirm_userdata]", $dane["content_user_confirm_userdata"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
											<?=sm_inputfield( "checkbox", "Zgoda na reklamy", "", "dane_content_user_confirm_marketing", "dane[content_user_confirm_marketing]", $dane["content_user_confirm_marketing"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
										</fieldset>

									</div>
								</div>

							</div>
							<div id="tabs-2" class="tab-pane">
								<div class="row-fluid">
									<div class="span6">

										<fieldset class="no-legend">
											<table class=table>
												<thead>
													<tr>
														<th width=25></th>
														<th width="100%"><b><?=__("core", "Nazwa grupy")?></b></th>
													</tr>
												</thead>
												<tbody>
<?
	if($result=content_usergroup_fetch_all()){
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$even=$even==1?0:1;
?>
													<tr valign=top class=<?=($even?"even":"odd")?>>
														<td>
															<?=sm_inputfield( "checkbox", "", "", "", "dane_content_usergroup[".$row["content_usergroup__id"]."]", $dane_content_usergroup[$row["content_usergroup__id"]], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
														</div>
														<td><a href="<?=$ENGINE?>/content_usergroup.php?content_usergroup__id=<?=$row["content_usergroup__id"]?>"><?=$row["content_usergroup__name"]?></a></td>
													</tr>
<?
		}
	}
?>
												</tbody>
											</table>
										</fieldset>

									</div>
									<div class="span6">

<?/* Status dostępu */ ?>
										<div class="fieldset-title">
											<div>Status dostępu</div>
										</div>
										<fieldset class="no-legend">
<?
	$inputfield_options = array();
	foreach($CONTENT_USER_STATUS_ARRAY AS $k=>$v){
		$inputfield_options[$k] = $v["name"];
	}
?>
											<?=sm_inputfield( "select", "Status użytkownika", "", "dane_content_user__status", "dane[content_user__status]", $dane["content_user__status"], "normal", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
											<div class="row-fluid">
												<div class="span6">
													<?=sm_inputfield( "text", "Identyfikator", "", "dane_content_user__username", "dane[content_user__username]", $dane["content_user__username"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
												<div class="span6">
													<?=sm_inputfield( "text", "Hasło", "", "dane_content_user__password", "dane[content_user__password]", "", "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
												</div>
											</div>
											<dl>
												<dt><?=__("core", "Ostatnie poprawne zalogowanie")?></dt>
												<dd><?=date("Y-m-d H:i:s", $dane["content_user__login_correct"])?></dd>
												<dt><?=__("core", "Ostatnie błędne logowanie")?></dt>
												<dd><?=date("Y-m-d H:i:s", $dane["content_user__login_false"])?></dd>
											</dl>
										</fieldset>

										<div class="fieldset-title">
											<div>Dostęp z adresów IP</div>
										</div>
										<fieldset class="no-legend">
											<?=sm_inputfield( "textarea", "Lista IP", "Lista adresów ip z których możliwy jest dostęp", "dane_content_user__admin_hostallow", "dane[content_user__admin_hostallow]", $dane["content_user__admin_hostallow"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=3);?>
										</fieldset>
									</div>
								</div>

							</div>
							<div id="tabs-3" class="tab-pane">

								<fieldset class="no-legend">
									<table class=table>
										<thead>
											<tr>
												<th width="100%"><?=__("core", "Typ dostępu")?></th>
												<th width=50><?=__("core", "Aktywny")?></th>
											</tr>
										</thead>
										<tbody>
<?
	if ($result=content_useracl_fetch_by_user( $content_user__id )) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$content_useracl[ $row["content_access__id"] ] = 1;
		}
	}
        if ($result=content_access_fetch_all()){
                $even="";
                while($row=$result->fetch(PDO::FETCH_ASSOC)){
                        $even=$even==1?0:1;
?>
											<tr class=<?=($even?"even":"odd")?>>
												<td><?=$row["content_access__name"]?> <i>(<?=$row["content_access__tag"]?>)</i></td>
												<td align=center><input type="checkbox" name="set[<?=$row["content_access__id"]?>]" <?=($content_useracl[$row["content_access__id"]]?"checked":"")?>></td>
											</tr>
<?
                }
        }
?>
										</tbody>
									</table>
								</fieldset>

							</div>
							<div id="tabs-4" class="tab-pane">

<?/* Pola dodatkowe */ ?>
								<fieldset class="no-legend">
<? include "_contentextra_formfields.php"; ?>
								</fieldset>
							</div>
						</div>
<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<input type=hidden name="dane[content_user__id]" value="<?=$dane["content_user__id"]?>">
						<input type=hidden name="content_user__id" value="<?=$content_user__id?>">
						<div class="btn-toolbar">
<?	if ($dane["content_user__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?	} else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<?	}?>
						</div>
<? } ?>

<?

if($content_user__id){
        if($result=content_useracl_fetch_by_user($dane["content_user__id"])){
                while($row=$result->fetch(PDO::FETCH_ASSOC)){
                        $content_useracl[$row["content_access__id"]] = $row["content_useracl_bit"];
                }
        }
}
?>
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
