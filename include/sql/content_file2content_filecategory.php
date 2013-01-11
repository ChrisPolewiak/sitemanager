<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_file2content_filecategory
 */

/**
 * @category	content_file2content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_file2content_filecategory_edit( $content_file__id, $content_filecategory__id ) {

	$dane["record_create_date"] = time();
	$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_file2content_filecategory VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_file__id)."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_filecategory__id)."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file2content_filecategory_edit()",$SQL_QUERY,$e); }
	return 1;
}

/**
 * @category	content_file2content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_file2content_filecategory_fetch_by_content_filecategory( $content_filecategory__id ) {

	$SQL_QUERY  = "SELECT f.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_file2content_filecategory AS f2fc \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_file AS f ON f.content_file__id=f2fc.content_file__id \n";
	$SQL_QUERY .= "WHERE f2fc.content_filecategory__id='". sm_secure_string_sql( $content_filecategory__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file2content_filecategory_fetch_by_content_filecategory()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_file2content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_file2content_filecategory_delete( $content_file__id, $content_filecategory__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_file2content_filecategory \n";
	$SQL_QUERY .= "WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."' \n";
	$SQL_QUERY .= "  AND content_filecategory__id='". sm_secure_string_sql( $content_filecategory__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file2content_filecategory_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_file2content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_file2content_filecategory_delete_by_content_file( $content_file__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_file2content_filecategory \n";
	$SQL_QUERY .= "WHERE content_file__id='". sm_secure_string_sql( $content_file__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_file2content_filecategory_delete_by_content_file()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

?>