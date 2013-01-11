<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_section2content_page
 */

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/
function content_section2content_page_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_section__id"] && $dane["content_page__id"]) {
		$tmp_dane = content_section2content_page_get( $dane["content_section__id"], $dane["content_page__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_section2content_page__column"] = $dane["content_section2content_page__column"] ? $dane["content_section2content_page__column"] : 0;
	$dane["content_section2content_page__order"] = $dane["content_section2content_page__order"] ? $dane["content_section2content_page__order"] : 0;
	$dane["content_section2content_page__requiredaccess"] = $dane["content_section2content_page__requiredaccess"] ? $dane["content_section2content_page__requiredaccess"] : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_section2content_page VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section2content_page__column"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section2content_page__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section2content_page__requiredaccess"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section2content_page__data"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section2content_page_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/
function content_section2content_page_delete( $content_section__id, $content_page__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_section2content_page \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."' \n";
	$SQL_QUERY .= "  AND content_page__id='". sm_secure_string_sql( $content_page__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section2content_page_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/
function content_section2content_page_get( $content_section__id, $content_page__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section2content_page \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."' \n";
	$SQL_QUERY .= "  AND content_page__id='". sm_secure_string_sql( $content_page__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section2content_page_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/
function content_section2content_page_fetch_by_content_section( $content_section__id ) {

	$SQL_QUERY  = "SELECT section2page.*, page.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section2content_page AS section2page \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_page AS page \n";
	$SQL_QUERY .= "  ON page.content_page__id = section2page.content_page__id \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."' \n";
	$SQL_QUERY .= "ORDER BY page.content_page__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section2content_page_fetch_by_content_section()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/
function content_section2content_page_delete_by_content_section( $content_section__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_section2content_page \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."'";

/**
 * @category	content_section2content_page
 * @package		sql
 * @version		5.0.0
*/

	return $result->rowCount();
}

?>