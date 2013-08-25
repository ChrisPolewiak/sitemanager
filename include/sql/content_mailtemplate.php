<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_mailtemplate
 */

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate_add( $dane ) {
	$dane["content_mailtemplate__id"] = "0";
	return content_mailtemplate_edit( $dane );
}

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.2
*/
function content_mailtemplate_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_mailtemplate__id"]) {
		$tmp_dane = content_mailtemplate_dane( $dane["content_mailtemplate__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_mailtemplate__id"], "content_mailtemplate", $tmp_dane, "edit" );
	}
	else {
		$dane["content_mailtemplate__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_mailtemplate__id"], "content_mailtemplate", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_mailtemplate VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__sysname"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__htmlbody"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__textbody"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__subject"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__sender_name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_mailtemplate__sender_email"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate_edit()",$SQL_QUERY,$e); }

	return $dane["content_mailtemplate__id"];
}

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate_delete( $content_mailtemplate__id ) {

	if ($deleted = content_mailtemplate_dane( $content_mailtemplate__id ) ) {
		core_changed_add( $content_mailtemplate__id, "content_mailtemplate", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_mailtemplate \n";
	$SQL_QUERY .= "WHERE content_mailtemplate__id='". sm_secure_string_sql( $content_mailtemplate__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate_dane( $content_mailtemplate__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_mailtemplate \n";
	$SQL_QUERY .= "WHERE content_mailtemplate__id='". sm_secure_string_sql( $content_mailtemplate__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate_get_by_sysname( $content_mailtemplate__sysname ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_mailtemplate \n";
	$SQL_QUERY .= "WHERE content_mailtemplate__sysname='". sm_secure_string_sql( $content_mailtemplate__sysname)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate_get_by_sysname()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_mailtemplate
 * @package		sql
 * @version		5.0.0
*/
function content_mailtemplate_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_mailtemplate \n";
	$SQL_QUERY .= "ORDER BY content_mailtemplate__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_mailtemplate_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>