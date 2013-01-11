<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_configadminview
 */

/**
 * @category	core_configadminview
 * @package		sql
 * @version		5.0.0
*/
function core_configadminview_add( $dane ) {
	$dane["core_configadminview__id"] = 0;
	return core_configadminview_edit($dane);
}

/**
 * @category	core_configadminview
 * @package		sql
 * @version		5.0.0
*/
function core_configadminview_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["core_configadminview__id"]) {
		$tmp_dane = core_configadminview_dane( $dane["core_configadminview__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["core_configadminview__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["core_configadminview__button_back"] = $dane["core_configadminview__button_back"] ? 1 : 0;
	$dane["core_configadminview__button_addnew"] = $dane["core_configadminview__button_addnew"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_configadminview VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__tag"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__dbname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__mainkey"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__function"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__button_back"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__button_addnew"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_configadminview__rowperpage"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminview_edit()",$SQL_QUERY,$e); }

	return $dane["core_configadminview__id"];
}

/**
 * @category	core_configadminview
 * @package		sql
 * @version		5.0.0
*/
function core_configadminview_dane( $core_configadminview__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_configadminview \n";
	$SQL_QUERY .= "WHERE core_configadminview__id='". sm_secure_string_sql( $core_configadminview__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminview_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	core_configadminview
 * @package		sql
 * @version		5.0.0
*/
function core_configadminview_get_by_adminview( $core_configadminview__tag, $content_user__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_configadminview \n";
	$SQL_QUERY .= "WHERE core_configadminview__tag='". sm_secure_string_sql( $core_configadminview__tag)."' \n";
	$SQL_QUERY .= "  AND content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminview_get_by_adminview()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	core_configadminview
 * @package		sql
 * @version		5.0.0
*/
function core_configadminview_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_configadminview \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_configadminview_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>