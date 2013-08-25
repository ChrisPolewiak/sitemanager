<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_configadminviewcolumn
 */

/**
 * @category	core_configadminviewcolumn
 * @package		sql
 * @version		5.0.0
*/
function core_configadminviewcolumn_add( $dane ) {
	$dane["core_configadminviewcolumn__id"] = "0";
	return core_configadminviewcolumn_edit( $dane );
}

/**
 * @category	core_configadminviewcolumn
 * @package		sql
 * @version		5.0.1
*/
function core_configadminviewcolumn_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["core_configadminviewcolumn__id"]) {
		$tmp_dane = core_configadminviewcolumn_dane( $dane["core_configadminviewcolumn__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["core_configadminviewcolumn__id"], "core_configadminviewcolumn", $tmp_dane, "edit" );
	}
	else {
		$dane["core_configadminviewcolumn__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["core_configadminviewcolumn__id"], "core_configadminviewcolumn", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];


	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_configadminviewcolumn VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__idcol"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__title"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__width"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__value"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__align"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminviewcolumn__order"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminviewcolumn_edit()",$SQL_QUERY,$e); }

	return $dane["core_configadminviewcolumn__id"];
}

/**
 * @category	core_configadminviewcolumn
 * @package		sql
 * @version		5.0.0
*/
function core_configadminviewcolumn_dane( $core_configadminviewcolumn__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_configadminviewcolumn \n";
	$SQL_QUERY .= "WHERE core_configadminviewcolumn__id='". sm_secure_string_sql( $core_configadminviewcolumn__id)."' ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminviewcolumn_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	core_configadminviewcolumn
 * @package		sql
 * @version		5.0.0
*/
function core_configadminviewcolumn_fetch_by_adminview( $core_configadminview__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_configadminviewcolumn \n";
	$SQL_QUERY .= "WHERE core_configadminview__id='". sm_secure_string_sql( $core_configadminview__id)."' \n";
	$SQL_QUERY .= "ORDER BY core_configadminviewcolumn__idcol ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminviewcolumn_fetch_by_adminview()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>