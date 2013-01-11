<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$type = $_REQUEST["type"];
$query = $_REQUEST["query"];

include "_page_header.php";
?>
      <table cellspacing=0 cellpadding=2 border=0 width="100%">
        <tr>
          <td colspan=2>
            <table cellspacing=0 cellpadding=4 border=0 width="100%">
              <tr bgcolor="#436AB8">
                <td align=center class="tool_name"><?=__("core", "Wyszukiwanie")?></td>
                <td align=right>
                  <a href="javascript:window.close();" class="tool_name"><b><?=__("core", "zamknij")?></b></a>
                </td>
              </tr>
            </table>
            <br>

<?
if (!function_exists($type."_staff_search")){
	echo __("core", "Nie zdefiniowano wyszukiwarki dla obiektu").": ".$type.".";
}
else {
?>
            <table cellspacing=2 cellpadding=2 bgcolor="#F1F2F2" width="100%">
<form action="<?=$ENGINE?>/<?=$page?>">
              <tr>
                <td width="100%"><input class=form type=text name=query value="<?=$query?>" style="width:100%"></td>
                <td width=80><input class=formb type=submit value="<?=__("core", "szukaj")?>"></td>
              </tr>
<input type=hidden name="type" value="<?=$type?>">
<input type=hidden name="backurl" value="<?=$backurl?>">
</form>
            </table>
<?
}
?>
            <table cellspacing=0 cellpadding=4 border=0 width="100%" height="100%">
              <tr valign=top>
                <td>


<?
if ( $query ) {

	$query = ereg_replace("%", "", $query);

	if (strlen($query)<1)
		error( __("core", "Należy podać minimum 1 znak") );

	if ($query) {

		if(function_exists($type."_staff_search")){
			eval(" \$result = ".$type."_staff_search( \"$query\" ); ");

			if ($result) {
				$found = $result->rowCount();
				print "<br><b>".__("core", "Ilość znalezionych rekordów").": ".$found."</b><br>\n";
				while ( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
					$wynix[] = $row;
					$first_letter[substr($row["res"],0,1)]++;
				}
				foreach($first_letter AS $letter=>$null) {
					$letters_line .= "<td><a href=\"#step_".$letter."\">".strtoupper($letter)."</a></td>";
				}
				$letters_line = "<img src=0 width=1 height=8><br><table align=center width=\"100%\" cellspacing=1 cellpadding=3 bgcolor=\"#E0E0E0\"><tr bgcolor=\"#F1F2F2\">".$letters_line."<td width=0><img src=0 width=1 height=12></td></tr></table><img src=0 width=1 height=3><br>";

				$find_status = 1;
				foreach($wynix AS $null=>$row) {

					$row["res"] = rznij_tekst(stripslashes($row["res"]), 50, false);
					$row["res"] = preg_replace("/($query)/i", "<b>\\1</b>", $row["res"]);

					$letter = substr($row["res"],0,1);
					if ($letter != $prev_letter) {
						echo "<a name=\"step_".$letter."\"></a>";
						$change_letter=true;
					}

					if ($change_letter) {
						echo $letters_line;
						$change_letter=false;
					}

					echo "<a style=\"text-decoration:none\" href=\"javascript:go('".strtolower($backurl)."?id_".$type."=".$row["id"]."');\">";
					echo $row["res"]."&nbsp(".$row["id"].")";
					echo "</a><br>\n";

					$prev_letter = $letter;
				}
			}
			else {
				if ( is_array( $ERROR ) ) {
					echo "<b><li>".join("<li>", $ERROR)."<br><br></b>\n";
				}
				echo __("core", "Nic nie znaleziono")."<br>".__("core", "Spróbuj inaczej sprecyzować swoje zapytanie...");
			}
		}
		else {
			echo __("core", "Nie zdefiniowano wyszukiwarki dla obiektu").": ".$type.".";
		}
	}
}
?>
                </td>
              </tr>
            </table>

<script language="JavaScript"><!--
	doc["query"].focus();
//--></script>

<? include "_page_footer.php"; ?>
