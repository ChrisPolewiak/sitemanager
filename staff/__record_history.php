				<div class="fieldset-title">
					<div><?=__("core", "RECORD_HISTORY__TITLE")?></div>
				</div>
				<fieldset class="record-history no-legend">
					<dl>
<?if($dane["record_create_date"]) { ?>
						<dt><?=__("core", "RECORD_HISTORY__CREATED")?></dt>
						<dd><?=($dane["record_create_date"] ? date("Y-m-d H:i:s", $dane["record_create_date"]) : "")?></dd>
<?	if($__content_user=content_user_get_by_id( $dane["record_create_id"] )) { ?>
						<dt><?=__("core", "RECORD_HISTORY__BY")?></dt>
						<dd><?=$__content_user["content_user__firstname"]?> <?=$__content_user["content_user__surname"]?></dd>
<?	} ?>
<?} ?>
						<dt><?=__("core", "RECORD_HISTORY__MODIFIED")?></dt>
						<dd><?=($dane["record_modify_date"] ? date("Y-m-d H:i:s", $dane["record_modify_date"]) : "")?></dd>
<? if($__content_user=content_user_get_by_id( $dane["record_modify_id"] )) { ?>
						<dt><?=__("core", "RECORD_HISTORY__BY")?></dt>
						<dd><?=$__content_user["content_user__firstname"]?> <?=$__content_user["content_user__surname"]?></dd>
<? } ?>
					</dl>
				</fieldset>
