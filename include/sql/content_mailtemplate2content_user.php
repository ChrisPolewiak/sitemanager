<?
/**
 * content_mailtemplate2content_user
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	content_mailtemplate2content_user
 */

/**
 * @category	content_mailtemplate2content_user
 * @package		sql
 * @version		5.1.0
*/
function content_mailtemplate2content_user_edit( $dane ) {

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$dane["content_mailtemplate2content_user__id"] = $dane["content_mailtemplate2content_user__id"] ? $dane["content_mailtemplate2content_user__id"] : uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_mailtemplate2content_user VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate2content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate2content_user_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_mailtemplate2content_user
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate2content_user_delete( $content_mailtemplate2content_user__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_mailtemplate2content_user \n";
	$SQL_QUERY .= "WHERE content_mailtemplate2content_user__id='". sm_secure_string_sql( $content_mailtemplate2content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate2content_user_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_mailtemplate2content_user
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate2content_user_get( $content_mailtemplate2content_user__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_mailtemplate2content_user \n";
	$SQL_QUERY .= "WHERE content_mailtemplate2content_user__id='". sm_secure_string_sql( $content_mailtemplate2content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate2content_user_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_mailtemplate2content_user
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate2content_user_fetch_by_content_mailtemplate( $content_mailtemplate__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_mailtemplate2content_user AS mailaction2user \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_user AS user \n";
	$SQL_QUERY .= "  ON user.content_user__id = mailaction2user.content_user__id \n";
	$SQL_QUERY .= "WHERE content_mailtemplate__id='". sm_secure_string_sql( $content_mailtemplate__id)."' \n";
	$SQL_QUERY .= "ORDER BY user.content_user__username \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate2content_user_fetch_by_content_mailtemplate()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_mailtemplate2content_user
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate2content_user_delete_by_content_mailtemplate( $content_mailtemplate__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_mailtemplate2content_user \n";
	$SQL_QUERY .= "WHERE content_mailtemplate__id='". sm_secure_string_sql( $content_mailtemplate__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate2content_user_delete_by_content_mailtemplate()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

?>