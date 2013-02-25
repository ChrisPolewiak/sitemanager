<?
/**
 * content_access
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_access
 */

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_add( $dane ) {
	$dane["content_access__id"] = "0";
	return content_access_edit( $dane );
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_access__id"]) {
		$tmp_dane = content_access_dane( $dane["content_access__id"] );
		$dane["content_access__locked"] = $tmp_dane["content_access__locked"];
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_access__id"], "content_access", $tmp_dane, "edit" );
	}
	else {
		$dane["content_access__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_access__id"], "content_access", $tmp_dane="", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_access__locked"] = $dane["content_access__locked"] ? 1 : 0;

	$content_access__id = $dane["content_access__id"] ? $dane["content_access__id"] : uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_access VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_access__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_access__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_access__tags"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_access__locked"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_access__message"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_edit()",$SQL_QUERY,$e); }

	return $dane["content_access__id"];
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_delete( $content_access__id ) {

	if ($deleted = content_access_dane( $content_access__id ) ) {
		core_changed_add( $content_access__id, "content_access", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_access \n";
	$SQL_QUERY .= "WHERE content_access__id='". sm_secure_string_sql( $content_access__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_dane( $content_access__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_access \n";
	$SQL_QUERY .= "WHERE content_access__id='". sm_secure_string_sql( $content_access__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_access \n";
	$SQL_QUERY .= "ORDER BY content_access__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_get_by_id( $content_access__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_access \n";
	$SQL_QUERY .= "WHERE content_access__id ='". sm_secure_string_sql( $content_access__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_get_by_id()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_access
 * @package		sql
 * @version		5.0.0
*/
function content_access_get_by_sysname( $content_access_sysname ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_access \n";
	$SQL_QUERY .= "WHERE content_access__tags LIKE '|". sm_secure_string_sql( $content_access_sysname)."|' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_access_get_by_sysname()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

?>