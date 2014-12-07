<?

$d = dir($INCLUDE_DIR."/sql");
while (false !== ($entry = $d->read()))
{
	if(preg_match("/.php$/", $entry) && $entry!="init.php")
	{
#		sm_trace( "include sql/".$entry );
		require $INCLUDE_DIR."/sql/".$entry;
	}
}

function sitemanager_mysql_foundrows() {

	$SQL_QUERY  = "SELECT FOUND_ROWS() AS total\n";
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("sitemanager_mysql_foundrows()",$SQL_QUERY,$e); }

	if($result->rowCount()>0)
	{
		$row=$result->fetch(PDO::FETCH_ASSOC);
		return $row["total"];
	}
	else
		return 0;
}

?>
