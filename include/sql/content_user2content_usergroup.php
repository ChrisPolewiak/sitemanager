<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	content_user2content_usergroup
 */

/**
 * @category	content_user2content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_user2content_usergroup_edit( $content_user__id, $content_usergroup__id ) {

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_user2content_usergroup VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_user__id)."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_usergroup__id)."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_usergroup_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_user2content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_user2content_usergroup_fetch_by_content_user( $content_user__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user2content_usergroup \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_usergroup_fetch_by_content_user()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_user2content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_user2content_usergroup_fetch_by_content_usergroup( $content_usergroup__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user2content_usergroup AS u2g, \n";
	$SQL_QUERY .= "     ".DB_TABLEPREFIX."_content_user AS u \n";
	$SQL_QUERY .= "WHERE u2g.content_usergroup__id='". sm_secure_string_sql( $content_usergroup__id)."' \n";
	$SQL_QUERY .= "  AND u.content_user__id=u2g.content_user__id \n";
	$SQL_QUERY .= "ORDER BY u.content_user__username \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_usergroup_fetch_by_content_usergroup()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_user2content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_user2content_usergroup_delete( $content_user__id, $content_usergroup__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user2content_usergroup \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";
	$SQL_QUERY .= "  AND content_usergroup__id='". sm_secure_string_sql( $content_usergroup__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_usergroup_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_user2content_usergroup
 * @package		sql
 * @version		5.0.0
*/
function content_user2content_usergroup_delete_by_content_user( $content_user__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user2content_usergroup \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_usergroup_delete_by_content_user()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

?>