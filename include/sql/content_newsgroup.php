<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_newsgroup
 */

/**
 * @category	content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_newsgroup_add( $dane ) {
	$dane["content_newsgroup__id"] = "0";
	return content_newsgroup_edit( $dane );
}

/**
 * @category	content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_newsgroup_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_newsgroup__id"]) {
		$tmp_dane = content_newsgroup_dane( $dane["content_newsgroup__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["content_newsgroup__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_newsgroup__published"] = $dane["content_newsgroup__published"] ? 1 : 0;

	$content_newsgroup__id = $dane["content_newsgroup__id"] ? $dane["content_newsgroup__id"] : uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_newsgroup VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_newsgroup__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_newsgroup__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_newsgroup__tag"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_newsgroup_edit()",$SQL_QUERY,$e); }

	return $dane["content_newsgroup__id"];
}

/**
 * @category	content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_newsgroup_delete( $content_newsgroup__id ) {

	if ($deleted = content_newsgroup_dane( $content_newsgroup__id ) ) {
		core_deleted_add( $content_newsgroup__id, "content_newsgroup", $deleted );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_newsgroup \n";
	$SQL_QUERY .= "WHERE content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_newsgroup_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_newsgroup_dane( $content_newsgroup__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_newsgroup \n";
	$SQL_QUERY .= "WHERE content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_newsgroup_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_newsgroup_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_newsgroup \n";
	$SQL_QUERY .= "ORDER BY content_newsgroup__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_newsgroup_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>