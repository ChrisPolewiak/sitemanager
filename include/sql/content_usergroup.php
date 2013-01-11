<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_usergroup
 */

/**
 * @category	content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_usergroup_add( $dane ) {
	$dane["content_usergroup__id"] = "0";
	return content_usergroup_edit( $dane );
}

/**
 * @category	content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_usergroup_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_usergroup__id"]) {
		$tmp_dane = content_usergroup_dane( $dane["content_usergroup__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["content_usergroup__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_usergroup VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_usergroup__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_usergroup__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_usergroup_edit()",$SQL_QUERY,$e); }

	return $dane["content_usergroup__id"];
}

/**
 * @category	content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_usergroup_delete( $content_usergroup__id ) {

	if ($deleted = content_usergroup_dane( $content_usergroup__id ) ) {
		core_deleted_add( $content_usergroup__id, "content_usergroup", $deleted );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_usergroup \n";
	$SQL_QUERY .= "WHERE content_usergroup__id='". sm_secure_string_sql( $content_usergroup__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_usergroup_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_usergroup_dane( $content_usergroup__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_usergroup \n";
	$SQL_QUERY .= "WHERE content_usergroup__id='". sm_secure_string_sql( $content_usergroup__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_usergroup_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_usergroup_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_usergroup \n";
	$SQL_QUERY .= "ORDER BY content_usergroup__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_usergroup_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>