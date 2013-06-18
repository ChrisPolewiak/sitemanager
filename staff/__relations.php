<fieldset>
	<legend><?=__("core", "Powiązania rekordu")?></legend>

	<table class=table width="100%">
		<thead>
			<tr>
				<td><?=__("core", "Objekt")?></td>
				<td><?=__("core", "Rekord")?></td>
				<td><?=__("core", "Kierunek")?></td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<?=__("core", "Przypisz ten rekord do")?>:<br>
	<div class="input-field">
		<select name="relation[dsttable]" style="width:100%" id="relationDsttable">
			<option value="content_user"><?=__("core", "Użytkownik")?></option>
			<option value="contentfile"><?=__("core", "Pliki")?></option>
			<option value="content_page"><?=__("core", "Strona")?></option>
		</select>
	</div>
	<?=sm_inputfield(array(
		"type"	=> "text",
		"title"	=> "Szukaj rekordu",
		"help"	=> "",
		"id"	=> "relation_search",
		"name"	=> "relation[search]",
		"value"	=> "",
		"size"	=> "xlarge",
		"disabled" => 0,
		"validation" => 0,
		"prepend" => 0,
		"append" => 0,
		"rows" => 1,
		"options" => "",
		"xss_secured" => true
	));?>
	<input type=hidden name="relation[dstid]" value="" id="relationDstid">
	<a href="#" OnClick="window.location='?srctable=<?=$__table?>&id_<?=$__table?>=<?=$__id_table?>&dsttable=' + document.getElementById('relation-dsttable').value + '&dstid=' + document.getElementById('relation-dstid').value + '&action[relation_add]=1'">dodaj</a>

<script>
jQuery(document).ready(function() {
	jQuery("#RelationSearch").autocomplete("/<?=$SM_ADMIN_PANEL?>/search-ajax.php?resulttype=pipe", {
		extraParams: {
			object: function() { return $("#relationDsttable").val(); }
		},
		selectFirst: false
	});
	jQuery("#relationDsttable").change(function() {
		jQuery("#RelationSearch").val('');
	});

	jQuery("#RelationSearch").result(function(event, data, formatted) {
		if (data)
			jQuery("#relationDstid").val(data[1]);
	});
} );
</script>

</fieldset>
