<?

sm_core_content_user_accesscheck($access_type_id."_READ",1);

$dane = $_REQUEST["dane"];
$core_task__id = $_REQUEST["core_task__id"];

include "_page_header5.php";

$dane = htmlentitiesall($dane);

?>
					<legend>Zadania</legend>
					<table class="table">
						<thead>
							<tr>
								<th>Plugin</th>
								<th>Funkcja</th>
								<th>Utworzone</th>
								<th>Wykonane</th>
								<th>Czas realizacji</th>
								<th>Wynik</th>
							</tr>
						</thead>
						<tbody>
<?
	if($result=core_task_fetch_all())
	{
		while($row=$result->fetch(PDO::FETCH_ASSOC))
		{
			$_params = json_decode($row["core_task__params"], true);
			$str  = $row["core_task__function"]."(";
			$str2="";
			foreach($_params AS $k=>$v){
				$str2 .= $str2 ? "," : "";
				$str2 .= " \$$k=\"$v\"";
			}
			$str .= $str2;
			$str .= " );\n";
?>
							<tr>
								<td><?=$row["core_task__plugin"]?></td>
								<td><?=$str?></td>
								<td><?=date("Y-m-d H:i:s",$row["record_create_date"])?></td>
								<td><?=$row["core_task__status"]!=0 ? date("Y-m-d H:i:s",$row["record_modify_date"]) : ""?></td>
								<td><?=$row["core_task__execution_time"]?> s.</td>
								<td><?=$row["core_task__result"]?></td>
							</tr>
<?
		}
	}
?>
						</tbody>
					</table>

<? include "_page_footer5.php" ?>