<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$content_file__id = $_REQUEST["content_file__id"];
$content_file__tableid = $_REQUEST["content_file__tableid"];
$content_file__table = $_REQUEST["content_file__table"];
$content_fileshowtypeitem__id = $_REQUEST["content_fileshowtypeitem__id"];
$backto = $_REQUEST["backto"];

if (isset($action["content_file_delete"])) {
	sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	content_fileassoc_delete( $content_file__id, $content_file__tableid, $content_file__table, $content_fileshowtypeitem__id );
	$url = "?content_file__tableid=".$content_file__tableid."&content_file__table=".$content_file__table."&backto=".$backto;
	header("Location: $url");
}
if (isset($action["content_file_dupe"])) {
	sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	content_fileassoc_edit( $content_file__id, $content_file__tableid, $content_file__table, "0", "0" );
	$url = "?content_file__tableid=".$content_file__tableid."&content_file__table=".$content_file__table."&backto=".$backto;
	header("Location: $url");
}
if (isset($action["save_showtype"])) {
	sm_core_content_user_accesscheck($access_type_id."_WRITE",1);
	foreach($dane_content_file__id AS $id=>$content_file__id){
		content_fileassoc_delete( $content_file__id, $content_file__tableid, $content_file__table, $dane_content_fileshowtypeitem__id_old[$id] );
		content_fileassoc_edit( $content_file__id, $content_file__tableid, $content_file__table, $content_fileassoc__order[$id], $dane_content_fileshowtypeitem__id[$id] );
	}
	$url = "?content_file__tableid=".$content_file__tableid."&content_file__table=".$content_file__table."&backto=".$backto;
	header("Location: $url");
}

if ($content_file__id){
	$dane_content_file = content_file_dane($content_file__id);
}

include "_page_header5.php";

?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a class="btn btn-small btn-info" href="<?=$backto?>"><i class="icon-list icon-white"></i>&nbsp;<?=__("core", "Wróć")?></a>
							<a class="btn btn-small btn-info" href="#" id="addWindow"><i class="icon-plus icon-white"></i>&nbsp;<?=__("core", "dodaj nowy załącznik")?></a>
						</div>
					</div>

					<form action="<?=$page?>" method=post enctype="multipart/form-data" id="sm-form">
						<fieldset class="no-legend">
							<table class="table">
								<thead>
									<tr>
										<th width=30>foto</th>
										<th width=150>nazwa pliku / opis dokumentu</th>
										<th width=50>rozmiar</th>
										<th width="100%">sposób prezentacji</th>
										<th width=30>kolejność</th>
										<th width=50>akcja</th>
									</tr>
								</thead>
								<tbody>
<?
if($result=content_fileassoc_fetch_by_multiid( $content_file__tableid, $content_file__table, "", "" )){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		$idcontent_fileassoc = $row["content_file__id"];
?>
									<tr valign=top>
										<td>
											<input type="hidden" name="dane_content_file__id[]" value="<?=$row["content_file__id"]?>">
<?
		if (preg_match("/^image/", $row["content_file__filetype"])) {
?>
											<img src="/cacheimg?id=<?=$row["content_file__id"]?>&w=40">
<?
		}
?>
										</td>
										<td>
											<a href="/cacheimg?id=<?=$row["content_file__id"]?>" target="_new"><?=$row["content_file__filename"]?></a><br>
											<?=$row["content_file__infoname"]?> <?=$row["content_file__infodescription"]?>
										</td>
										<td align=right><?=filesize_convert($row["content_file__filesize"])?></td>
										<td>
<?
		if (preg_match("/^image/", $row["content_file__filetype"])) {
?>
											<input type="hidden" name="dane_content_fileshowtypeitem__id_old[]" value="<?=$row["content_fileshowtypeitem__id"]?>">
<?
			$inputfield_options=array();
			$inputfield_options[]="-- nie pokazuj pliku --";
			foreach($CONTENT_FILESHOWTYPEITEM_ARRAY AS $k=>$v){
				if($v["showtype_sysname"] == $content_file__table) {
					$inputfield_options[ $v["id"] ] = $v["name"]." (".$k.")";
				}
			}
?>
											<?=sm_inputfield(array(
												"type"	=> "select",
												"title"	=> "",
												"help"	=> "",
												"id"	=> "",
												"name"	=> "dane_content_fileshowtypeitem__id[]",
												"value"	=> $row["content_fileshowtypeitem__id"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
<?
		}
?>
										</td>
										<td>
											<?=sm_inputfield(array(
												"type"	=> "text",
												"title"	=> "",
												"help"	=> "",
												"id"	=> "",
												"name"	=> "content_fileassoc__order[]",
												"value"	=> $row["content_fileassoc__order"],
												"size"	=> "block-level",
												"disabled" => 0,
												"validation" => 0,
												"prepend" => 0,
												"append" => 0,
												"rows" => 1,
												"options" => $inputfield_options,
												"xss_secured" => true
											));?>
										</td>
										<td>
<?		if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
											<a href="?action[content_file_delete]=1&content_file__id=<?=$row["content_file__id"]?>&content_fileassoc__id=<?php echo $_GET['content_fileassoc__id'] ?>&content_file__tableid=<?=$content_file__tableid?>&content_file__table=<?=$content_file__table?>&content_fileshowtypeitem__id=<?=$row["content_fileshowtypeitem__id"]?>&backto=<?=$backto?>" onclick="return confDelete()" title="Usuń"><i class="icon-remove"></i></a>
											<a href="?action[content_file_dupe]=1&content_file__id=<?=$row["content_file__id"]?>&content_fileassoc__id=<?php echo $_GET['content_fileassoc__id'] ?>&content_file__tableid=<?=$content_file__tableid?>&content_file__table=<?=$content_file__table?>&backto=<?=$backto?>" title="Klonuj"><i class="icon-tint"></i></a>
											<a href="<?=$ENGINE?>/contentfile.php?content_file__id=<?=$row["content_file__id"]?>" target="_new" title="Edytuj"><i class="icon-edit"></i></a>
<?		} ?>
										</td>
									</tr>
<?
	}
?>
								</tbody>
<?
}
?>
							</table>
						</fieldset>

<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
						<div class="btn-toolbar">
							<input type="hidden" name="content_file__tableid" value="<?=$content_file__tableid?>">
							<input type="hidden" name="content_file__table" value="<?=$content_file__table?>">
							<input type="hidden" name="content_fileassoc__id" value="<?=$content_fileassoc__id?>">
							<input type="hidden" name="backto" value="<?=$backto?>">

							<a class="btn btn-normal btn-info" id="action-save_showtype"><i class="icon-ok icon-white"></i>&nbsp;<?=__("core", "ZAPISZ")?></a>
						</div>
<? } ?>
<script>
$('#action-save_showtype').click(function() {
	$('#sm-form').append('<input type="hidden" name="action[save_showtype]" value=1>');
	$('#sm-form').submit();
});
</script>
					</form>

<script type="text/javascript">
	$('#addWindow').click(function(event){
		var windowWidth = 1200;
		var windowHeight = 800;
		var windowLeft = parseInt((screen.availWidth/2) - (windowWidth/2));
		var windowTop = parseInt((screen.availHeight/2) - (windowHeight/2));
		var windowSize = "width=" + windowWidth + ",height=" + windowHeight + "left=" + windowLeft + ",top=" + windowTop + "screenX=" + windowLeft + ",screenY=" + windowTop;

		var url = 'contentfile_popup.php?mode=multi&content_file__tableid=<?=$content_file__tableid?>&content_file__table=<?=$content_file__table?>';
		var windowName = "Gallery";
		
		window.open(url, windowName, windowSize,'_blank');
		
		event.preventDefault();
	});
</script>

<? include "_page_footer5.php"; ?>
