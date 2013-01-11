<?

exit;

admin_access_check($admin_type_id, "dane");

if ( isset($action["import"]) ) {
//	admin_access_check($admin_type_id, "add");
$file= file($_FILES["import_file"]["tmp_name"]);

$fields_show = split(";",$file[0]);

//print_r($file);

//	$content_crontab__id__id = content_crontab__id_add($dane);
}

page_staff_header("IMPORT DANYCH", 450);

$dane = htmlentitiesall($dane);

if( is_array( $ERROR ) ) form_error( $ERROR );

?>
<table cellspacing=0 cellpadding=2 border=0>
<form action="<?=$page?>" method=post enctype="multipart/form-data">
  <tr valign=top>
    <td>
      <?=InputField ("file", "<b>Plik z bazą danych w formacie CSV</b>", "import_file", 100, 0);?><br>
      <br>
<? if (sm_core_content_user_accesscheck($access_type_id."_WRITE")) { ?>
<input type=submit name="action[import]" value="IMPORT">
<? } ?>

    </td>
  </tr>
</form>
</table>
<br>
<?
if($fields_show){
?>
<div class=title>Przetwarzanie nagłówków</div>
W przesłanym dokumencie znaleziono następujące kolumny, przypisz do których pól dane mają zostać załadowane<br>
<div class=Table>
<table>
<?
	foreach($fields_show AS $k=>$v) {
		$rowid=$rowid=="TableRow1"?"TableRow2":"TableRow1";
?>
  <tr class="<?=$rowid?>">
    <td><?=($k+1)?>: <?=$v?></td>
    <td>
      <select name="sqlfield[<?=$k?>]" class=form>
        <option value=""> -- ignoruj --</option>
<?
		foreach($engine_sql_structure["contentnews"] AS $k2=>$v2) {
			if($v2["edit"])
			echo "<option value=\"".$k2."\"".($k2==$sqlfield[$k]?" selected":"")."> $k2: ".$v2["name"]."</option>";
		}
?>
      </select>
    </td>
  </tr>
<?
	}
?>
</table>
</div>
<?
}
?>

<? page_staff_footer(); ?>