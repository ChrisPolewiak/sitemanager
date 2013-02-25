<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$plugin = $_REQUEST["plugin"];

if ( $action=="enable" ) {


	foreach($SM_PLUGINS AS $tag=>$data){
		if($data["enabled"]){
			$plugin_to_save[$tag] = $data;
		}
		if($data["procedure_enable"] && $plugin==$tag) {
			require $data["dir"]."/plugin_enable.php";
		}
	}
	$plugin_to_save[$plugin] = $SM_PLUGINS[$plugin];
	sm_core_serialize_data( $plugin_to_save, "plugin" );
	header("Location: /admin/coreplugin.php");
	exit;
}

if ( $action=="disable" ) {
	foreach($SM_PLUGINS AS $tag=>$data){
		if($data["enabled"] && $tag != $plugin){
			$plugin_to_save[$tag] = $data;
		}
		if($data["procedure_disable"] && $plugin==$tag) {
			require $data["dir"]."/plugin_disable.php";
		}
	}
	sm_core_serialize_data( $plugin_to_save, "plugin" );
	header("Location: /admin/coreplugin.php");
	exit;
}

include "_page_header5.php";

$dane = htmlentitiesall($dane);

?>
					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">

						<fieldset class="no-legend">
							<table class="table">
								<thead>
									<tr>
										<th><?=__("CORE", "CORE_PLUGIN__THEAD_PLUGIN")?></th>
										<th><?=__("CORE", "CORE_PLUGIN__THEAD_AUTHOR")?></th>
										<th><?=__("CORE", "CORE_PLUGIN__THEAD_VERSION")?></th>
										<th><?=__("CORE", "CORE_PLUGIN__THEAD_STATUS")?></th>
									</tr>
								</thead>
								<tbody>
<?
foreach($SM_PLUGINS AS $k=>$v) {
?>
									<tr>
										<td>
											<b><?=$v["name"]?></b><br>
											<?=$v["description"]?>
										</td>
										<td><?=$v["author"]?></td>
										<td><?=$v["version"]?></td>
										<td>
<?	if($v["enabled"]=="1") { ?>
											<a href="?plugin=<?=$k?>&action=disable"><?=__("CORE", "CORE_PLUGIN__ACTION_DISABLE")?></a>
<?	} else { ?>
											<a href="?plugin=<?=$k?>&action=enable"><?=__("CORE", "CORE_PLUGIN__ACTION_ENABLE")?></a>
<?	} ?>
										</td>
									</tr>
<?
}
?>
								</tbody>
							</table>
						</fieldset>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<a class="btn btn-normal btn-info" id="action-edit"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "BUTTON__SAVE")?></a>
						</div>
<? } ?>
<script>
$('#action-edit').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[edit]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<? include "_page_footer5.php"; ?>
