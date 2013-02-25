<?
/**
 * content_text
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_text
 */

// dodanie nazwy obiektów do których można przypisać pliki
$CONTENT_FILESSHOWTYPE_AVAILABLEOBJECT[] = array("sysname"=>"content_text","name"=>"Artykuły");

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_add( $dane ) {
	$dane["content_text__id"] = "0";
	return content_text_edit( $dane );
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.1
*/
function content_text_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_text__id"] ) {
		$tmp_dane = content_text_dane( $dane["content_text__id"] );
		$dane["content_text__tableid"] = $tmp_dane["content_text__tableid"];
		$dane["content_text__table"] = $tmp_dane["content_text__table"];
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_text__id"], "content_text", $tmp_dane, "edit" );
	}
	else {
		$dane["content_text__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_text__id"], "content_text", $tmp_dane="", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_text__order"] = $dane["content_text__order"] ? $dane["content_text__order"] : 0;

	$content_text__id = $dane["content_text__id"] ? $dane["content_text__id"] : uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_text VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_section__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__lang"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__title"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__lead"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__body"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__order"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__tableid"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_text__table"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_edit()",$SQL_QUERY,$e); }

	return $dane["content_text__id"];
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_delete( $content_text__id ) {

	if ($deleted = content_text_dane( $content_text__id ) ) {
		core_changed_add( $content_text__id, "content_text", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_text__id='". sm_secure_string_sql( $content_text__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_dane( $content_text__id ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_text__id='". sm_secure_string_sql( $content_text__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_by_page( $content_page__id, $content_text__order="", $content_text__lang="" ) {

	$andsql=0;
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_page__id='". sm_secure_string_sql( $content_page__id)."' \n";
	if($content_text__order) {
		$SQL_QUERY .= " AND content_text__order='". sm_secure_string_sql( $content_text__order)."' \n";
	}
	if($content_text__lang) {
		$SQL_QUERY .= " AND content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_by_page()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_by_section( $content_section__id, $content_text__order="", $content_text__lang="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_section__id='". sm_secure_string_sql( $content_section__id)."' \n";
	if($content_text__order) {
		$SQL_QUERY .= "  AND content_text__order='". sm_secure_string_sql( $content_text__order)."' \n";
	}
	if($content_text__lang) {
		$SQL_QUERY .= " AND content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_by_section()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_by_section_sysname( $content_section__sysname, $content_text__order="", $content_text__lang="" ) {
	$SQL_QUERY  = "SELECT text.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text AS text \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_section AS section \n";
	$SQL_QUERY .= "  ON section.content_section__id=text.content_section__id \n";
	$SQL_QUERY .= "WHERE section.content_section__sysname='". sm_secure_string_sql( $content_section__sysname)."' \n";
	if($content_text__order) {
		$SQL_QUERY .= "  AND text.content_text__order='". sm_secure_string_sql( $content_text__order)."' \n";
	}
	if($content_text__lang) {
		$SQL_QUERY .= " AND text.content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY text.content_text__order, text.content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_by_section_sysname()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_by_table( $tableid, $table, $content_text__lang="" ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_text__tableid='". sm_secure_string_sql( $tableid)."' \n";
	$SQL_QUERY .= "  AND content_text__table='". sm_secure_string_sql( $table)."' \n";
	if($content_text__lang) {
		$SQL_QUERY .= " AND content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_by_table()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_count_by_table( $tableid, $table, $content_text__lang="" ) {
	$SQL_QUERY  = "SELECT COUNT(content_text__id) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_text__tableid='". sm_secure_string_sql( $tableid)."' \n";
	$SQL_QUERY .= "  AND content_text__table='". sm_secure_string_sql( $table)."' \n";
	if($content_text__lang) {
		$SQL_QUERY .= " AND content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_count_by_table()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_all() {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_fetch_all_new() {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text AS ct \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_page AS cp ON ct.content_page__id=cp.content_page__id \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_section AS cs ON cs.content_section__id=ct.content_section__id \n";
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_fetch_all_new()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_www_search($query, $content_text__lang="" ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE ( content_text__title like '%". sm_secure_string_sql( $query)."%' \n";
	$SQL_QUERY .= "OR content_text__lead like '%". sm_secure_string_sql( $query)."%' \n";
	$SQL_QUERY .= "OR content_text__body like '%". sm_secure_string_sql( $query)."%' ) \n";
	if($content_text__lang) {
		$SQL_QUERY .= " AND content_text__lang='". sm_secure_string_sql( $content_text__lang)."' \n";
	}
	$SQL_QUERY .= "ORDER BY content_text__order, content_text__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_www_search()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_text
 * @package		sql
 * @version		5.0.0
*/
function content_text_get_new( $content_page__id ) {
	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_text \n";
	$SQL_QUERY .= "WHERE content_page__id='". sm_secure_string_sql( $content_page__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_text_get_new()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>