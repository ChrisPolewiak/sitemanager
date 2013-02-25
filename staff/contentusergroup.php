<?
sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_usergroup__id = $_REQUEST["content_usergroup__id"];
$set = $_REQUEST["set"];

if( isset($action["add"]) || isset($action["edit"]) ){
	$dane=trimall($dane);
	if(!$dane["content_usergroup__name"]){
		$ERROR[]=__("core", "Podaj nazwę grupy");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_usergroup__id = content_usergroup_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_usergroup__id = content_usergroup_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_usergroup_delete($content_usergroup__id);
		unset($content_usergroup__id);
	}
	if ( isset($action["add"]) || isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	        content_usergroupacl_delete_content_usergroup($content_usergroup__id);
	        if($result=content_access_fetch_all()) {
	                while($row=$result->fetch(PDO::FETCH_ASSOC)) {
				$val = ($set[$row["content_access__id"]] ? 1 : 0);
				if($val) {
					content_usergroupacl_add( $row["content_access__id"], $content_usergroup__id, $val );
				}
	                }
	        }
	}
}
else {
	$content_usergroup__id = $content_usergroup__id ? $content_usergroup__id : "0";
}

if( $content_usergroup__id ) {
	$dane = content_usergroup_dane( $content_usergroup__id );
	if($result=content_usergroupacl_fetch_by_usergroup($dane["content_usergroup__id"])){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$content_usergroupacl[$row["content_access__id"]] = $row["content_usergroupacl__bit"];
		}
	}
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_usergroup__id && $content_usergroup__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_usergroup",
		"function_fetch" => "content_usergroup_fetch_all()",
		"mainkey" => "content_usergroup__id",
		"columns" => array(
			array( "title"=>__("core", "Nazwa grupy"), "width"=>"100%", "value"=>"%%{content_usergroup__name}%%", "order"=>1 ),
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
							<a class="btn btn-small btn-info" href="?content_usergroup__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<fieldset class="no-legend">
							<?=sm_inputfield( "text", "Nazwa grupy", "", "dane_content_usergroup__name", "dane[content_usergroup__name]", $dane["content_usergroup__name"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
						</fieldset>

						<div class="fieldset-title">
							<div>Role dla grupy</div>
						</div>
						<fieldset class="no-legend">
							<table class=table>
								<thead>
									<tr>
										<th width="100%"><?=__("core", "Rola")?></th>
										<th width=50><?=__("core", "Stan")?></th>
									</tr>
								</thead>
								<tbody>
<?
	if ($result=content_access_fetch_all()){
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$even=$even?0:1;
?>
									<tr class="<?=$even?"even":"odd"?>">
										<td><a href="content_access.php?content_access__id=<?=$row["content_access__id"]?>"><?=$row["content_access__name"]?></a></td>
										<td align=center><input type="checkbox" name="set[<?=$row["content_access__id"]?>]" <?=($content_usergroupacl[$row["content_access__id"]]?"checked":"")?>></td>
									</tr>
<?
		}
	}
?>
								</tbody>
							</table>
						</fieldset>


<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[content_usergroup__id]" value="<?=$dane["content_usergroup__id"]?>">
							<input type=hidden name="content_usergroup__id" value="<?=$content_usergroup__id?>">
<? if ($dane["content_usergroup__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<? } else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD")?></a>
<? }?>
						</div>
<? } ?>
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

	if($content_usergroup__id){
?>
					<div class="fieldset-title">
						<div>Lista użytkowników grupy</div>
					</div>
					<fieldset class="no-legend">
<?
	$params = array(
		"button_back" => 0,
		"button_addnew" => 0,
		"dbname" => "content_user2content_usergroup",
		"function_fetch" => "content_user2content_usergroup_fetch_by_content_usergroup('$content_usergroup__id')",
		"mainkey" => "content_user__id",
		"columns" => array(
			array( "title"=>__("core", "Identyfikator"), "width"=>"15%", "value"=>"%%{content_user__username}%%", "order"=>1, ),
			array( "title"=>__("core", "Nazwisko i imię"), "width"=>"30%", "align"=>"left", "value"=>"%%{content_user__surname}%% %%{content_user__firstname}%%", "order"=>1, ),
			array( "title"=>__("core", "Adres e-mail"), "width"=>"30%", "value"=>"%%{content_user__email}%%", "order"=>1, ),
			array( "title"=>__("core", "Status"), "width"=>"10%", "align"=>"center", "value"=>"%%{content_user__status}%%", "order"=>1, 
				"valuesmatch"=>array( 
					1=>"<div class=gray>".__("core", "Nowy - nieaktywny")."</div>",
					2=>"<div class=green>".__("core", "Aktywny")."</div>", 
					3=>"<div class=red>".__("core", "Zablokowany")."</div>"
				),
			),
			array( "title"=>__("core", "Ostatni dostęp"), "align"=>"center", "width"=>"15%", "value"=>"%%[date]{content_user__login_correct}%%", "order"=>1, ),
		),
		"row_per_page_default" => 100,
	);
	include "_datatable_list5.php";
?>
					</fieldset>
<?
	}
}
?>

<? include "_page_footer5.php"; ?>
