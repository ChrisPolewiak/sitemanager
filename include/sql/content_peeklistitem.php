<?
/**
 * content_peeklistitem
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_peeklistitem
 */

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_add( $dane ) {
	$dane["content_peeklistitem__id"] = "0";
	return content_peeklistitem_edit( $dane );
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.1
*/
function content_peeklistitem_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_peeklistitem__id"]) {
		$tmp_dane = content_peeklistitem_dane( $dane["content_peeklistitem__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["content_peeklistitem__id"];
		core_changed_add( $dane["content_peeklistitem__id"], "content_peeklistitem", $tmp_dane, "edit" );
	}
	else {
		$dane["content_peeklistitem__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_peeklistitem__id"], "content_peeklistitem", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_peeklistitem__published"] = $dane["content_peeklistitem__published"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_peeklistitem VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__order"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value01"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value02"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value03"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value04"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value05"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value06"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value07"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value08"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value09"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklistitem__value10"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_edit()",$SQL_QUERY,$e); }

	return $dane["content_peeklistitem__id"];
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_delete( $content_peeklistitem__id ) {

	if ($deleted = content_peeklistitem_dane( $content_peeklistitem__id ) ) {
		core_changed_add( $content_peeklistitem__id, "content_peeklistitem", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_peeklistitem \n";
	$SQL_QUERY .= "WHERE content_peeklistitem__id='". sm_secure_string_sql( $content_peeklistitem__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_dane( $content_peeklistitem__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklistitem \n";
	$SQL_QUERY .= "WHERE content_peeklistitem__id='". sm_secure_string_sql( $content_peeklistitem__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_get( $content_peeklistitem__id ) {

	$SQL_QUERY  = "SELECT *, UNIX_TIMESTAMP(content_peeklistitem__datetime) AS timestamp \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklistitem \n";
	$SQL_QUERY .= "WHERE content_peeklistitem__id='". sm_secure_string_sql( $content_peeklistitem__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_fetch_by_content_peeklist( $content_peeklist__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklistitem \n";
	$SQL_QUERY .= "WHERE content_peeklist__id='". sm_secure_string_sql( $content_peeklist__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_peeklistitem__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_fetch_by_content_peeklist()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_peeklistitem
 * @package		sql
 * @version		5.0.0
*/
function content_peeklistitem_count() {
	$SQL_QUERY  = "SELECT count(*) AS news_count \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklistitem \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklistitem_count()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_peeklistitem_count"];
	}
}

?>