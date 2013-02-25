<?
sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_access__id = $_REQUEST["content_access__id"];
$daneaccess = $_REQUEST["daneaccess"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["content_access__name"]){
		$ERROR[] = __("core", "Podaj nazwę poziomu dostępu");
	}
}

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane["content_access__tags"] = "|";
	foreach($daneaccess AS $k=>$v){
		$dane["content_access__tags"] .= $k."|";
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_access__id = content_access_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$content_access__id = content_access_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
        content_access_delete($content_access__id);
		unset($content_access__id);
	}
}
else {
	$content_access__id = $content_access__id ? $content_access__id : "0";
}

if( $content_access__id ) {
	$dane = content_access_dane( $content_access__id );
	$tmp = split("\|",$dane["content_access__tags"]);
	foreach($tmp AS $k=>$v){
		if($v) $content_access__tags[$v]=1;
	}
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);
?>
<?
if (!$content_access__id && $content_access__id!="0") {
	$params = array(
		"button_back" => 1,
		"button_addnew" => 1,
		"dbname" => "content_access",
		"function_fetch" => "content_access_fetch_all()",
		"mainkey" => "content_access__id",
		"columns" => array(
			array( "title"=>__("core", "Nazwa poziomu"), "width"=>"100%", "value"=>"%%{content_access__name}%%", "order"=>1, ),
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
							<a class="btn btn-small btn-info" href="?content_access__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("core", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=POST id="sm-form">
						<fieldset class="no-legend">
							<div class="row-fluid">
								<div class="span4">
										<?=sm_inputfield(
											"text",
											"Nazwa roli",
											"Nazwa wewnętrzna",
											"dane_content_access__name",
											"dane[content_access__name]",
											$dane["content_access__name"],
											"xlarge",
											$disabled=false,
											$validation=false,
											$prepend=false,
											$append=false,
											$rows=1
										);?>
										<?=sm_inputfield(
											"textarea",
											"Treść komunikatu dla użytkownika",
											"zostanie wyświetlony w przypadku napotkania braku dostępu",
											"dane_content_access__message",
											"dane[content_access__message]",
											$dane["content_access__message"],
											"xlarge",
											$disabled=false,
											$validation=false,
											$prepend=false,
											$append=false,
											$rows=5
										);?>
								</div>
								<div class="span8">
									<h4><?=__("core", "Poziomy dostępów służą do definiowania ról dostępu do systemu")?></h4>
									<p><?=__("core", "<b>Przykład 1:</b><br>
        <em>Chcemy by dany element systemu był dostępny do edycji wyłącznie dla wybranego grona osób - wystarczy stworzyć rolę, w której damy uprawnienia do edycji dla danego obiektu. I usuniemy te uprawnienia dla innych ról.
        Następnie dodajemy do określonych osób nową rolę, lub tworzymy dodatkową grupę użytkowników - dodajemy do niej tę rolę, a następnie grupę przypisujemy do tych osób.</em><br>
        <b>Przykład 2:</b><br>
        Jeśli potrzebujemy ograniczyć dostęp do danej strony serwisu - tworzymy pustą rolę (bez zaznaczania dostępów), następnie wybraną rolę przypisujemy do użytkowników/grup zaś na panelu zarządzania drzewem stron określamy, że dostęp do danej podstrony wymaga tej nowej roli.</em>")?>
        								</p>
								</div>
							</div>
						</fieldset>

<script>
$('#tabs').ready(function() {
	$('#tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$(".colselect").click( function () {
		var classid = $(this).attr("id");
		var status = $("input#"+classid).attr('checked');
		if(status) $("input."+classid).removeAttr('checked'); else $("input."+classid).attr('checked', 'checked');
	});
	$(".rowselect").click( function () {
		var classid = $(this).attr("id");
		var status = $("input#"+classid).attr('checked');
		if(status) $("input."+classid).removeAttr('checked'); else $("input."+classid).attr('checked', 'checked');
	});
});
</script>

<?
	$tabs_array[0] = array(
		"tag" => "CORE",
		"name" => __("core", "Uprawnienia systemowe")
		);
	foreach($SM_PLUGINS AS $plugin_id=>$plugin_data) {
		$tabs_array[] = array(
			"tag"=>$plugin_id,
			"name"=>$plugin_data["name"]
		);
	}

?>
						<ul class="nav nav-tabs" id="tabs">
<? foreach($tabs_array AS $plugin_id=>$plugin_data) { ?>
							<li <?=($plugin_id=="CORE"?"class=\"active\"":"")?>><a href="#tabs-<?=$plugin_id?>"><?=$plugin_data["name"]?></a></li>
<? } ?>
						</ul>

						<div class="tab-content">
<?
	foreach($tabs_array AS $plugin_id=>$plugin_data) {
?>
							<div id="tabs-<?=$plugin_id?>" class="tab-pane <?=($plugin_id=="CORE"?"active":"")?>">
								<fieldset class="no-legend">
									<table class=table>
										<thead>
											<tr>
												<th width=300>Nazwa obiektu</td>
<?
		foreach($SYSTEM_DEFINED_ROLEACTION[ $plugin_data["tag"] ] AS $roleaction_letter=>$roleaction_data) {
?>
												<th width=75 align=center class="colselect" id="roleaction-<?=$plugin_id?>-<?=$roleaction_letter?>" style="cursor:pointer;">
													<?=$roleaction_data["name"]?>
													<input type="checkbox" id="roleaction-<?=$plugin_id?>-<?=$roleaction_letter?>" class="roleaction-<?=$plugin_id?>-<?=$roleaction_letter?>" style="display:none">
												</th>
<?
		}
?>
											</tr>
										</thead>
										<tbody>
<?	
		$even="";
		foreach($SYSTEM_DEFINED_ROLES[ $plugin_data["tag"] ] AS $role_name=>$role_data) {
			$even=$even?false:true;
			$role_action_letters = preg_split("//",$role_data["actions"]);
			$role_action_letters = array_flip($role_action_letters);
?>
											<tr class="<?=$even?"even":"odd"?>">
												<td class="rowselect" id="role-<?=$plugin_id?>-<?=$role_name?>" style="cursor:pointer;">
													<?=$role_data["name"]?>
													<input type="checkbox" id="role-<?=$plugin_id?>-<?=$role_name?>" class="role-<?=$plugin_id?>-<?=$role_name?>" style="display:none">
												</td>
<?
			foreach($SYSTEM_DEFINED_ROLEACTION[ $plugin_data["tag"] ] AS $roleaction_letter=>$roleaction_data) {
				if($role_action_letters[$roleaction_letter]) {
?>
												<td align=center>
													<input class="roleaction-<?=$plugin_id?>-<?=$roleaction_letter?> role-<?=$plugin_id?>-<?=$role_name?>" type=checkbox name="daneaccess[<?=$plugin_data["tag"]?>_<?=$role_name?>_<?=$roleaction_data["tag"]?>]" <?=($content_access__tags[ $plugin_data["tag"] ."_". $role_name ."_". $roleaction_data["tag"] ]?"checked":"")?>>
												</td>
<?	
				}
				else {
?>
												<td>&nbsp;</td>
<?
				}
			}
?>
											</tr>
<?
		}
?>
										</tbody>
									</table>
								</fieldset>
							</div>	
<?
	}
?>
						</div>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<input type=hidden name="dane[content_access__id]" value="<?=$dane["content_access__id"]?>">
						<input type=hidden name="content_access__id" value="<?=$content_access__id?>">
						<div class="btn-toolbar">
<? if ($dane["content_access__id"]) {?>
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
}
?>

<? include "_page_footer5.php"; ?>
