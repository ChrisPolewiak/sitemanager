<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_template
 */

/**
 * @category	content_template
 * @package		sql
 * @version		5.0.0
*/
function content_template_add( $dane ) {
	$dane["content_template__id"] = "0";
	return content_template_edit( $dane );
}

/**
 * @category	content_template
 * @package		sql
 * @version		5.0.1
*/
function content_template_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_template__id"]) {
		$tmp_dane = content_template_dane( $dane["content_template__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_template__id"], "content_template", $tmp_dane, "edit" );
	}
	else {
		$dane["content_template__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_template__id"], "content_template", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_template VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__srcfile"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__lang"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_template_edit()",$SQL_QUERY,$e); }

	return $dane["content_template__id"];
}

/**
 * @category	content_template
 * @package		sql
 * @version		5.0.0
*/
function content_template_delete( $content_template__id ) {

	if ($deleted = content_template_dane( $content_template__id ) ) {
		core_changed_add( $content_template__id, "content_template", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_template \n";
	$SQL_QUERY .= "WHERE content_template__id='". sm_secure_string_sql( $content_template__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_template_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_template
 * @package		sql
 * @version		5.0.0
*/
function content_template_dane( $content_template__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_template \n";
	$SQL_QUERY .= "WHERE content_template__id='". sm_secure_string_sql( $content_template__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_template_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_template
 * @package		sql
 * @version		5.0.0
*/
function content_template_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_template \n";
	$SQL_QUERY .= "ORDER BY content_template__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_template_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>