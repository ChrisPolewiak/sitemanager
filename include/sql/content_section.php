<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_section
 */

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_add( $dane ) {
	$dane["content_section__id"] = "0";
	return content_section_edit( $dane );
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_section__id"]) {
		$tmp_dane = content_section_dane( $dane["content_section__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_section__id"], "content_section", $tmp_dane, "edit" );
	}
	else {
		$dane["content_section__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_section__id"], "content_section", $tmp_dane="", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_section VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__sysname"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__title"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_edit()",$SQL_QUERY,$e); }

	return $dane["content_section__id"];
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_delete( $content_section__id ) {

	if ($deleted = content_section_dane( $content_section__id ) ) {
		core_changed_add( $content_section__id, "content_section", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_section \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_get_by_sysname( $content_section__sysname ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section \n";
	$SQL_QUERY .= "WHERE content_section__sysname LIKE '". sm_secure_string_sql( $content_section__sysname)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_get_by_sysname()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_dane( $content_section__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section \n";
	$SQL_QUERY .= "ORDER BY content_section__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_section
 * @package		sql
 * @version		5.0.0
*/
function content_section_fetch_by_page( $content_page__id, $column="", $order="" ) {

	$SQL_QUERY  = "SELECT section.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_section AS section \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_section2content__page AS section2page \n";
	$SQL_QUERY .= "ON section2page.content_section__id=section.content_section__id \n";
	$SQL_QUERY .= "WHERE section2page.content_page__id='". sm_secure_string_sql( $content_page__id)."' \n";
	if($column) {
		$SQL_QUERY .= "  AND section.content_section__column='". sm_secure_string_sql( $column)."' \n";
	}
	if($order) {
		$SQL_QUERY .= "  AND section.content_section__order='". sm_secure_string_sql( $order)."' \n";
	}
	else {
		$SQL_QUERY .= "ORDER BY section.content_section__column, section.content_section__order";
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_section_fetch_by_page()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>