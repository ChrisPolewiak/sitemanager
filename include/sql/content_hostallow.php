<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_hostallow
 */

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_add( $dane ) {
	$dane["content_hostallow__id"] = "0";
	return content_hostallow_edit( $dane );
}

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_hostallow__id"]) {
		$tmp_dane = content_hostallow_dane( $dane["content_hostallow__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["content_hostallow__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_hostallow__active"] = $dane["content_hostallow__active"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_hostallow VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_hostallow__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_hostallow__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_hostallow__hosts"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_hostallow__active"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_hostallow_edit()",$SQL_QUERY,$e); }

	return $dane["content_hostallow__id"];
}

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_delete( $content_hostallow__id ) {

	if ($deleted = content_hostallow_dane( $content_hostallow__id ) ) {
		core_deleted_add( $content_hostallow__id, "content_hostallow", $deleted );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_hostallow \n";
	$SQL_QUERY .= "WHERE content_hostallow__id='". sm_secure_string_sql( $content_hostallow__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_hostallow_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_dane( $content_hostallow__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_hostallow \n";
	$SQL_QUERY .= "WHERE content_hostallow__id='". sm_secure_string_sql( $content_hostallow__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_hostallow_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_hostallow \n";
	$SQL_QUERY .= "ORDER BY content_hostallow__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_hostallow_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_hostallow
 * @package		sql
 * @version		5.0.0
*/
function content_hostallow_fetch_active() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_hostallow \n";
	$SQL_QUERY .= "WHERE content_hostallow__active=1 \n";
	$SQL_QUERY .= "ORDER BY content_hostallow__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_hostallow_fetch_active()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>