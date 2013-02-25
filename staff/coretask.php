<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$core_task__id = $_REQUEST["core_task__id"];

if ( isset($action["add"]) || isset($action["edit"]) ) {
	$dane=trimall($dane);
	if(!$dane["core_task__name"]){
		$ERROR[]=__("core", "Podaj nazwę skryptu");
	}
	if(!$dane["core_task__mhdmd"]){
		$ERROR[]=__("core", "Podaj konfigurację");
	}
}

if(!is_array($ERROR)) {
	if ( isset($action["add"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_task__id = core_task_add($dane);
	}
	elseif ( isset($action["edit"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		$core_task__id = core_task_edit($dane);
	}
	elseif ( isset($action["delete"]) ) {
		sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
		core_task_delete($core_task__id);
		unset($core_task__id);
	}
}
else {
	$core_task__id = $core_task__id ? $core_task__id : "0";
}


if( $core_task__id > 0 ) {
	$dane = core_task_dane( $core_task__id );
	print_r($dane);
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

if (!$core_task__id && $core_task__id!="0") {
?>
					<legend>Zadania</legend>
					<table class="table">
						<thead>
							<tr>
								<th>Plugin</th>
								<th>Funkcja</th>
								<th>Status</th>
								<th>Dodana</th>
								<th>Przez</th>
							</tr>
						</thead>
						<tbody>
<?
	if($result=core_task_fetch_all()){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
?>
							<tr>
								<td><?=$row["core_task__plugin"]?></td>
								<td><?=$row["core_task__function"]?></td>
								<td><?=$row["core_task__status"]?></td>
								<td><?=date("Y-m-d H:i:s",$row["record_create_date"])?></td>
								<td><?=$row["content_user__firstname"]?> <?=$row["content_user__surname"]?></td>
							</tr>
<?
		}
	}
?>
						</tbody>
						</tfoot>
							<tr>
								<td colspan=5>
									<a class="admin-button" href="?core_task__id=0"><span><?=__("CORE", "BUTTON__ADD_NEW")?></span></a>
								</td>
							</tr>
						</tfoot>
					</table>
<?
}
else {
?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="?"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "BUTTON__BACK_TO_LIST")?></a>
							<a class="btn btn-small btn-info" href="?core_task__id=0"><i class="icon-plus-sign icon-white"></i>&nbsp;<?=__("CORE", "BUTTON__ADD_NEW")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
							<?=sm_inputfield( "text", "Nazwa operacji", "używana wewnętrznie", "dane_content_text__title", "dane[content_text__title]", $dane["content_text__title"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1);?>
						</fieldset>

<?	if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type=hidden name="dane[core_task__id]" value="<?=$dane["core_task__id"]?>">
							<input type=hidden name="core_task__id" value="<?=$core_task__id?>">
<?		if ($dane["core_task__id"]) {?>
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
							<a class="btn btn-normal btn-danger" id="action-delete"><i class="icon-remove icon-white" onclick="return confDelete()"></i>&nbsp;<?=__("core", "BUTTON__DELETE")?></a>
<?		} else {?>
							<a class="btn btn-normal btn-info" id="action-add"><i class="icon-ok icon-white"></i>&nbsp;<?=__("CORE", "BUTTON__SAVE")?></a>
<?		}?>
						</div>
<?	} ?>
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
</script>					</form>
					</form>
<?
}
?>

<? include "_page_footer5.php" ?>
