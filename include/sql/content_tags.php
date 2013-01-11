<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_tags
 */

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_edit( $content_tags__tag, $content_tags__tableid, $content_tags__table, $create_date, $create_id ) { 

	$content_tags__tag = trim(strip_tags($content_tags__tag));

	if($create_date != "" && $create_id != ""){
		$dane["record_create_date"] = $create_date;
		$dane["record_create_id"] = $create_id;
	}
	else{
		$dane["record_create_date"] = time();
		$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_tags VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_tags__tag)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_tags__tableid)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_tags__table)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";
 
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_delete( $content_tags__tag, $content_tags__tableid, $content_tags__table ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "WHERE content_tags__tag='". sm_secure_string_sql( $content_tags__tag)."' \n";
	$SQL_QUERY .= "  AND content_tags__tableid='". sm_secure_string_sql( $content_tags__tableid)."' \n";
	$SQL_QUERY .= "  AND content_tags__table='". sm_secure_string_sql( $content_tags__table)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_delete_by_id( $content_tags__tableid, $content_tags__table ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "WHERE content_tags__tableid='". sm_secure_string_sql( $content_tags__tableid)."' \n";
	$SQL_QUERY .= "  AND content_tags__table='". sm_secure_string_sql( $content_tags__table)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_delete_by_id()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_clear_by_id( $content_tags__tableid, $content_tags__table ) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "SET content_tags__tag = '' \n";
	$SQL_QUERY .= "WHERE content_tags__tableid='". sm_secure_string_sql( $content_tags__tableid)."' \n";
	$SQL_QUERY .= "  AND content_tags__table='". sm_secure_string_sql( $content_tags__table)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_clear_by_id()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_fetch_by_id( $content_tags__tableid, $content_tags__table ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "WHERE content_tags__tableid = '". sm_secure_string_sql( $content_tags__tableid)."' \n";
	$SQL_QUERY .= "  AND content_tags__table = '". sm_secure_string_sql( $content_tags__table)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_by_id()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_fetch_by_tag( $content_tags__tag ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "WHERE content_tags__tag = '". sm_secure_string_sql( $content_tags__tag)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_by_tag()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_tags
 * @package		sql
 * @version		5.0.0
*/
function content_tags_fetch_all( $limit=100 ){

	$SQL_QUERY  = "SELECT content_tags__tag, count(*) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_tags \n";
	$SQL_QUERY .= "GROUP BY content_tags__tag \n";
	$SQL_QUERY .= "ORDER BY ile DESC \n";
	$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $limit)." \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>