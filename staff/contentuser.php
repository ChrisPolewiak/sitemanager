<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_user__id = $_REQUEST["content_user__id"];
$dane_content_usergroup = $_REQUEST["dane_content_usergroup"];
$set = $_REQUEST["set"];

if( isset($action["add"]) || isset($action["edit"]) )
	$dane=trimall($dane);

if( isset($action["add"]) || $dane["content_user__password"] )
	content_user_password_check( $dane["content_user__password"], "", $dane["content_user__username"] );

if(!is_array($ERROR))
{
	if ( isset($action["add"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_user__id = content_user_add($dane);
	}

	elseif ( isset($action["edit"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_user__id = content_user_edit($dane);
		content_user2content_access_delete_by_user( $content_user__id );
		if($content_user__id && is_array($set))
		{
			foreach($set AS $__content_access__id=>$null)
				content_user2content_access_add( 0, $__content_access__id, $content_user__id, 1 );
		}
	}

	elseif ( isset($action["delete"]) )
	{
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		content_user_disable($dane["content_user__id"]);
	}

	if( isset($action["add"]) || isset($action["edit"]) )
	{
		if (is_array($dane_content_usergroup))
		{
			content_user2content_usergroup_delete_by_content_user($content_user__id);
			foreach($dane_content_usergroup AS $k=>$v)
				content_user2content_usergroup_edit( 0, $content_user__id, $k );
		}
	}
}
else
	$content_user__id = $content_user__id ? $content_user__id : "0";

if( $content_user__id )
{
	$dane = content_user_dane( $content_user__id );
	unset($dane_content_usergroup);
	if($result = content_user2content_usergroup_fetch_by_content_user( $content_user__id ))
	{
		while($row=$result->fetch(PDO::FETCH_ASSOC))
			$dane_content_usergroup[$row["content_usergroup__id"]]=1;
	}
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$content_user__id && $content_user__id!="0")
{
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_user",
		"function_fetch" => "content_user_fetch_all()",
		"mainkey" => "content_user__id",
		"columns" => array(
			array( "title"=>"Identyfikator", "width"=>"100%", "value"=>"%%{content_user__username}%%", "order"=>1, ),
			array( "title"=>"Ostatni dostęp", "width"=>"110", "value"=>"%%[date]{content_user__login_correct}%%", ),
			array( "title"=>"Status", "align"=>"center", "value"=>"%%{content_user__status}%%", "order"=>1,
				"valuesmatch"=>array(
		                    1=>"<div class=gray>Nowy - nieaktywny</div>",
		                    2=>"<div class=green>Aktywny</div>",
		                    3=>"<div class=red>Zablokowany</div>"
                ),
			),

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
							<li class="active"><a href="#tabs-1"><?=__("core", "Dane konta")?></a></li>
							<li><a href="#tabs-2"><?=__("core", "Uprawnienia Indywidualne")?></a></li>
						</ul>

						<div class="tab-content">
							<div id="tabs-1" class="tab-pane active">
								<div class="row-float">
									<div class="span6">

										<fieldset class="no-legend">
											<?=sm_inputfield(array(
												"type"=>"text",
												"title"=>"Identyfikator",
												"help"=>"",
												"id"=>"dane_content_user__username",
												"name"=>"dane[content_user__username]",
												"value"=>$dane["content_user__username"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>1,
												"options"=>"",
												"xss_secured"=>true
											));?>
											<br>
											<?=sm_inputfield(array(
												"type"=>"text",
												"title"=>"Hasło",
												"help"=>"",
												"id"=>"dane_content_user__password",
												"name"=>"dane[content_user__password]",
												"value"=>"",
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>1,
												"prepend"=>0,
												"append"=>0,
												"rows"=>1,
												"options"=>"",
												"xss_secured"=>true
											));?>
										</fieldset>

										<div class="fieldset-title">
											<div>Grupy użytkowników</div>
										</div>
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
	if($result=content_usergroup_fetch_all())
	{
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC))
		{
			$even=$even==1?0:1;
?>
													<tr valign=top class=<?=($even?"even":"odd")?>>
														<td>
															<?=sm_inputfield(array(
																"type"=>"checkbox",
																"title"=>"",
																"help"=>"",
																"id"=>"",
																"name"=>"dane_content_usergroup[".$row["content_usergroup__id"]."]",
																"value"=>$dane_content_usergroup[$row["content_usergroup__id"]],
																"size"=>"block-level",
																"disabled"=>0,
																"validation"=>0,
																"prepend"=>0,
																"append"=>0,
																"rows"=>1,
																"options"=>"",
																"xss_secured"=>true
															));?>
														</div>
														<td><a href="<?=$ENGINE?>/contentusergroup.php?content_usergroup__id=<?=$row["content_usergroup__id"]?>"><?=$row["content_usergroup__name"]?></a></td>
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
	foreach($CONTENT_USER_STATUS_ARRAY AS $k=>$v)
		$inputfield_options[$k] = $v["name"];
?>
											<?=sm_inputfield(array(
												"type"=>"select",
												"title"=>"Status użytkownika",
												"help"=>"",
												"id"=>"dane_content_user__status",
												"name"=>"dane[content_user__status]",
												"value"=>$dane["content_user__status"],
												"size"=>"normal",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>1,
												"options"=>$inputfield_options,
												"xss_secured"=>true
											));?>
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
											<?=sm_inputfield(array(
												"type"=>"textarea",
												"title"=>"Lista IP",
												"help"=>"Lista adresów ip z których możliwy jest dostęp do panelu admin",
												"id"=>"dane_content_user__admin_hostallow",
												"name"=>"dane[content_user__admin_hostallow]",
												"value"=>$dane["content_user__admin_hostallow"],
												"size"=>"block-level",
												"disabled"=>0,
												"validation"=>0,
												"prepend"=>0,
												"append"=>0,
												"rows"=>3,
												"options"=>"",
												"xss_secured"=>true
											));?>
										</fieldset>
									</div>
								</div>

							</div>
							<div id="tabs-2" class="tab-pane">

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
	if ($result=content_user2content_access_fetch_by_user( $content_user__id ))
	{
		while($row=$result->fetch(PDO::FETCH_ASSOC))
			$content_useracl[ $row["content_access__id"] ] = 1;
	}
	if ($result=content_access_fetch_all())
	{
		$even="";
		while($row=$result->fetch(PDO::FETCH_ASSOC))
		{
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
<?
	if (sm_core_content_user_accesscheck($access_type_id."_WRITE"))
	{
?>
						<input type=hidden name="dane[content_user__id]" value="<?=$dane["content_user__id"]?>">
						<input type=hidden name="content_user__id" value="<?=$content_user__id?>">
						<div class="btn-toolbar">
<?
		if ($dane["content_user__id"])
		{
?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
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
<?
	}
?>

<?

	if($content_user__id)
	{
		if($result=content_user2content_access_fetch_by_user($dane["content_user__id"]))
		{
			while($row=$result->fetch(PDO::FETCH_ASSOC))
				$content_useracl[$row["content_access__id"]] = $row["content_useracl_bit"];
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