<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_extralist
 */

/**
 * @category	content_extralist
 * @package		sql
 * @version		5.0.0
*/
function content_extralist_add( $dane ) {
	$dane["content_extralist__id"] = "0";
	return content_extralist_edit( $dane );
}

/**
 * @category	content_extralist
 * @package		sql
 * @version		5.0.0
*/
function content_extralist_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_extralist__id"]) {
		$tmp_dane = content_extralist_dane( $dane["content_extralist__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_extralist__id"], "content_extralist", $tmp_dane, "edit" );
	}
	else {
		$dane["content_extralist__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_extralist__id"], "content_extralist", $tmp_dane="", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_extralist VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extralist__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extralist__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extralist__value"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extralist_edit()",$SQL_QUERY,$e); }

	return $dane["content_extralist__id"];
}

/**
 * @category	content_extralist
 * @package		sql
 * @version		5.0.0
*/
function content_extralist_delete( $content_extralist__id ) {

	if ($deleted = content_extralist_dane( $content_extralist__id ) ) {
		core_changed_add( $content_extralist__id, "content_extralist", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_extralist \n";
	$SQL_QUERY .= "WHERE content_extralist__id='". sm_secure_string_sql( $content_extralist__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extralist_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_extralist
 * @package		sql
 * @version		5.0.0
*/
function content_extralist_dane( $content_extralist__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_extralist \n";
	$SQL_QUERY .= "WHERE content_extralist__id='". sm_secure_string_sql( $content_extralist__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extralist_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_extralist
 * @package		sql
 * @version		5.0.0
*/
function content_extralist_fetch_by_content_extra( $content_extra__id, $order="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_extralist \n";
	$SQL_QUERY .= "WHERE content_extra__id='". sm_secure_string_sql( $content_extra__id)."' \n";
	switch($order){
		case "name": default:
			$SQL_QUERY .= "ORDER BY content_extralist__name ";
			break;
		case "value": default:
			$SQL_QUERY .= "ORDER BY content_extralist__value ";
			break;
	}
		
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extralist_fetch_by_content_extra()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>