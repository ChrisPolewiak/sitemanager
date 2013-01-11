<?

function core_config_edit( $dane ) {
	$dane = trimall($dane);

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_config VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_config__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_config__value"])."'\n";
	$SQL_QUERY .= ")\n";
 	if ( $GLOBALS["SM_PDO"]->query($SQL_QUERY) or sqlerr("core_config_edit()",$SQL_QUERY) )
 		return 1;
}

#
##
#

function core_config_get_by_name( $core_config__name ) {
	$SQL_QUERY  = "SELECT core_config__value \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_config \n";
	$SQL_QUERY .= "WHERE core_config__name='". sm_secure_string_sql( $core_config__name)."'";

	$result = $GLOBALS["SM_PDO"]->query($SQL_QUERY) or sqlerr("core_configadminview_get_by_adminview()",$SQL_QUERY);
	if($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["core_config__value"];
	}
	else 
		return 0;
}

#
##
#

function core_config_fetch_all() {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_config \n";
	$result = $GLOBALS["SM_PDO"]->query($SQL_QUERY) or sqlerr("core_config_fetch_all()",$SQL_QUERY);
	return ($result->rowCount()>0 ? $result : 0);
}

?>