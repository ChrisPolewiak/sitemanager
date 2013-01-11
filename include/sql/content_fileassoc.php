<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_fileassoc
 */

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_edit(
	$content_file__id,
	$content_fileassoc__tableid,
	$content_fileassoc__table,
	$content_fileassoc__order="",
	$content_fileshowtypeitem__id
	) { 

	$dane["record_create_date"] = time();
	$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];

	$content_fileassoc__order = $content_fileassoc__order ? $content_fileassoc__order : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_fileassoc VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_file__id)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_fileassoc__tableid)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_fileassoc__table)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_fileassoc__order)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_fileshowtypeitem__id)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_delete( $content_file__id, $content_fileassoc__tableid, $content_fileassoc__table, $content_fileshowtypeitem__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_fileassoc \n";
	$SQL_QUERY .= "WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."' \n";
	$SQL_QUERY .= "  AND content_fileassoc__tableid='". sm_secure_string_sql( $content_fileassoc__tableid)."' \n";
	$SQL_QUERY .= "  AND content_fileassoc__table='". sm_secure_string_sql( $content_fileassoc__table)."' \n";
	$SQL_QUERY .= "  AND content_fileshowtypeitem__id='". sm_secure_string_sql( $content_fileshowtypeitem__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_delete_by_content_file( $content_file__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_fileassoc \n";
	$SQL_QUERY .= "WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_delete_by_content_file()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_delete_by_record( $content_fileassoc__tableid, $content_fileassoc__table ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_fileassoc \n";
	$SQL_QUERY .= "WHERE content_fileassoc__tableid='". sm_secure_string_sql( $content_fileassoc__tableid)."' \n";
	$SQL_QUERY .= "  AND content_fileassoc__table='". sm_secure_string_sql( $content_fileassoc__table)."'\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_delete_by_record()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_fetch_by_multiid( $multiid, $content_fileassoc__table, $content_file_type="", $content_fileshowtypeitem__sysname="" ) {
	global $CONTENT_FILESHOWTYPEITEM_ARRAY;

	if($CONTENT_FILESHOWTYPEITEM_ARRAY[$content_fileshowtypeitem__sysname]) {
		$content_fileshowtypeitem__id = $CONTENT_FILESHOWTYPEITEM_ARRAY[$content_fileshowtypeitem__sysname]["id"];
	}

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileassoc AS cfa \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_file AS cf ON cf.content_file__id=cfa.content_file__id \n";
	$SQL_QUERY .= "WHERE \n";
	if (is_array($multiid)){
		$SQL_QUERY .= "cfa.content_fileassoc__tableid IN ('".join("','", $multiid)."') \n";
	}
	else {
		$SQL_QUERY .= "cfa.content_fileassoc__tableid = '". sm_secure_string_sql( $multiid)."' \n";
	}
	$SQL_QUERY .= "  AND cfa.content_fileassoc__table='". sm_secure_string_sql( $content_fileassoc__table)."' \n";
	if (is_array($content_file_type)){
		$SQL_QUERY .= "  AND cf.content_file_type IN ('".join("','", $content_file_type)."') \n";
	}
	elseif ($content_file_type) {
		$SQL_QUERY .= "  AND cf.content_file_type LIKE '". sm_secure_string_sql( $content_file_type)."' \n";
	}
	if ($content_fileshowtypeitem__id) {
		$SQL_QUERY .= "  AND cfa.content_fileshowtypeitem__id = '". sm_secure_string_sql( $content_fileshowtypeitem__id)."' \n";
	}
	$SQL_QUERY .= "ORDER BY cfa.content_fileassoc__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_fetch_by_multiid()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
	
}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_get($content_file__id, $content_fileassoc__tableid, $content_fileassoc__table){

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileassoc \n";
	$SQL_QUERY .= "WHERE content_file__id = '". sm_secure_string_sql( $content_file__id)."' \n";
	$SQL_QUERY .= "  AND content_fileassoc__tableid = '". sm_secure_string_sql( $content_fileassoc__tableid)."' \n";
	$SQL_QUERY .= "  AND content_fileassoc__table = '". sm_secure_string_sql( $content_fileassoc__table)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);

}

/**
 * @category	content_fileassoc
 * @package		sql
 * @version		5.0.0
*/
function content_fileassoc_dane($content_file__id, $content_file_id){

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileassoc as cfa \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_file AS cf ON cf.content_file__id=cfa.content_file__id \n";
	$SQL_QUERY .= "WHERE cfa.content_file__id = '". sm_secure_string_sql( $content_file__id)."' AND cfa.content_fileassoc__tableid = '". sm_secure_string_sql( $content_file_id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileassoc_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>