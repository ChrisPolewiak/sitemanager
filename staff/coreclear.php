<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$type = $_REQUEST["type"];

if ( isset($action["clear"]) ) {
	sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	switch($type) {
		case "core_task_all":
			core_task_delete_by_status( "0", 0 );
			break;
		case "core_task":
			core_task_delete_by_status( "1", 0 );
			break;
		case "core_changed_add":
			core_changed_delete_by_state( "add", 0 );
			break;
		case "core_changed_edit":
			core_changed_delete_by_state( "edit", 0 );
			break;
		case "core_changed_delete":
			core_changed_delete_by_state( "delete", 0 );
			break;
	}


	header("Location: ?");
	exit;
}
else if ( isset($action["save"]) ) {
	sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	$content_section__id = content_section_add($dane);
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<div class="fieldset-title">
							<div>Zadania asynchroniczne</div>
						</div>
<?
	$core_task_total = core_task_count_by_status();
	$core_task_wait = core_task_count_by_status( $status="1" );
?>
						<fieldset class="no-legend">
							Łączna liczba zadań asynchronicznych w bazie: <?=$core_task_total?> <a href="?action[clear]=1&type=core_task_all">czyść wszystkie zadania</a><br>
							Liczba zadań asynchronicznych zrealizowanych: <?=$core_task_wait?> <a href="?action[clear]=1&type=core_task">czyść dane</a><br>
<?/*
							<?=sm_inputfield(array(
								"type"=>"checkbox",
								"title"=>"Czy czyścić automatycznie?",
								"help"=>"",
								"id"=>"dane_core_task_clear_status",
								"name"=>"dane[core_task_clear_status]",
								"value"=> $dane["core_task_clear_status"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
							));?>
<?
	$select_options = array(1,2,3,4,5,6,7,8,9,10);
?>
							<?=sm_inputfield(array(
								"type"=>"select",
								"title"=>"Po ilu dniach dane mają być czyszczone?",
								"help"=>"",
								"id"=>"dane_core_task_clear_delay",
								"name"=>"dane[core_task_clear_delay]",
								"value"=> $dane["core_task_clear_delay"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>$select_options,
								"xss_secured"=>true
							));?>
*/?>
						</fieldset>

						<div class="fieldset-title">
							<div>Bufor zmian</div>
						</div>
<?
	$core_changed_total = core_changed_count_by_state();
	$core_changed_add = core_changed_count_by_state( "add" );
	$core_changed_edit = core_changed_count_by_state( "edit" );
	$core_changed_delete = core_changed_count_by_state( "delete" );
?>
						<fieldset class="no-legend">
							Łączna ilość zmian: <?=$core_changed_total?><br>
							W tym ilość operacji "dodaj": <?=$core_changed_add?> <a href="?action[clear]=1&type=core_changed_add">czyść dane</a><br>

<?/*
							<?=sm_inputfield(array(
								"type"=>"checkbox",
								"title"=>"Czy czyścić automatycznie operacje 'dodaj'?",
								"help"=>"",
								"id"=>"dane_core_changed_add_status",
								"name"=>"dane[core_changed_add_status]",
								"value"=> $dane["core_changed_add_status"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
							));?>
<?
	$select_options = array(1,2,3,4,5,6,7,8,9,10);
?>
							<?=sm_inputfield(array(
								"type"=>"select",
								"title"=>"Po ilu dniach operacje 'dodaj' mają być czyszczone?",
								"help"=>"",
								"id"=>"dane_core_changed_add_delay",
								"name"=>"dane[core_changed_add_delay]",
								"value"=> $dane["core_changed_add_delay"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>$select_options,
								"xss_secured"=>true
							));?>
*/?>

							W tym ilość operacji "zmiana": <?=$core_changed_edit?> <a href="?action[clear]=1&type=core_changed_edit">czyść dane</a><br>

<?/*
							<?=sm_inputfield(array(
								"type"=>"checkbox",
								"title"=>"Czy czyścić automatycznie operacje 'zmiana'?",
								"help"=>"",
								"id"=>"dane_core_changed_edit_status",
								"name"=>"dane[core_changed_edit_status]",
								"value"=> $dane["core_changed_edit_status"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
							));?>
<?
	$select_options = array(1,2,3,4,5,6,7,8,9,10);
?>
							<?=sm_inputfield(array(
								"type"=>"select",
								"title"=>"Po ilu dniach operacje 'zmiana' mają być czyszczone?",
								"help"=>"",
								"id"=>"dane_core_changed_edit_delay",
								"name"=>"dane[core_changed_edit_delay]",
								"value"=> $dane["core_changed_edit_delay"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>$select_options,
								"xss_secured"=>true
							));?>
*/?>

							W tym ilość operacji "usunięcie": <?=$core_changed_delete?> <a href="?action[clear]=1&type=core_changed_delete">czyść dane</a><br>

<?/*
							<?=sm_inputfield(array(
								"type"=>"checkbox",
								"title"=>"Czy czyścić automatycznie operacje 'usunięcie'?",
								"help"=>"",
								"id"=>"dane_core_changed_delete_status",
								"name"=>"dane[core_changed_delete_status]",
								"value"=> $dane["core_changed_delete_status"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
							));?>
<?
	$select_options = array(1,2,3,4,5,6,7,8,9,10);
?>
							<?=sm_inputfield(array(
								"type"=>"select",
								"title"=>"Po ilu dniach operacje 'usunięcie' mają być czyszczone?",
								"help"=>"",
								"id"=>"dane_core_changed_delete_delay",
								"name"=>"dane[core_changed_delete_delay]",
								"value"=> $dane["core_changed_delete_delay"],
								"size"=>"block-level",
								"disabled"=>0,
								"validation"=>0,
								"prepend"=>0,
								"append"=>0,
								"rows"=>1,
								"options"=>$select_options,
								"xss_secured"=>true
							));?>
*/?>
						</fieldset>
<?/*

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
								<div class="btn-toolbar">
									<a class="btn btn-info" id="action-save"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
								</div>
<? 	} ?>

*/?>
<script>
$('#action-save').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[save]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>
<? include "_page_footer5.php"; ?>
