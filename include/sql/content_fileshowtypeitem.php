<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_fileshowtypeitem
 */

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_add( $dane ) {
	$dane["content_fileshowtypeitem__id"] = "0";
	return content_fileshowtypeitem_edit( $dane );
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.1
*/
function content_fileshowtypeitem_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_fileshowtypeitem__id"]) {
		$tmp_dane = content_fileshowtypeitem_dane( $dane["content_fileshowtypeitem__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_fileshowtypeitem__id"], "content_fileshowtypeitem", $tmp_dane, "edit" );
	}
	else {
		$dane["content_fileshowtypeitem__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_fileshowtypeitem__id"], "content_fileshowtypeitem", $tmp_dane="", "add" );
	}

	$dane["content_fileshowtypeitem__default"] = $dane["content_fileshowtypeitem__default"] ? 1 : 0;

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"]   = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_fileshowtypeitem VALUES ( \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["content_fileshowtypeitem__id"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["content_fileshowtype__id"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["content_fileshowtypeitem__name"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["content_fileshowtypeitem__sysname"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["content_fileshowtypeitem__default"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["record_create_date"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["record_create_id"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["record_modify_date"] )."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql( $dane["record_modify_id"] )."' \n";
	$SQL_QUERY .= ") \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_edit()",$SQL_QUERY,$e); }

	return $dane["content_fileshowtypeitem__id"];
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_delete( $content_fileshowtypeitem__id ) {

	if ($deleted = content_fileshowtypeitem_dane( $content_fileshowtypeitem__id ) ) {
		core_changed_add( $content_fileshowtypeitem__id, "content_fileshowtypeitem", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_fileshowtypeitem \n";
	$SQL_QUERY .= "WHERE content_fileshowtypeitem__id = '".sm_secure_string_sql( $content_fileshowtypeitem__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_dane( $content_fileshowtypeitem__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtypeitem \n";
	$SQL_QUERY .= "WHERE content_fileshowtypeitem__id = '".sm_secure_string_sql( $content_fileshowtypeitem__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_get_default_by_contentfileshowtype( $content_fileshowtype__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtypeitem \n";
	$SQL_QUERY .= "WHERE content_fileshowtype__id = '".sm_secure_string_sql( $content_fileshowtype__id)."' \n";
	$SQL_QUERY .= "  AND content_fileshowtypeitem__default=1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_get_default_by_contentfileshowtype()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtypeitem \n";
	$SQL_QUERY .= "ORDER BY content_fileshowtypeitem__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_fileshowtypeitem
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtypeitem_fetch_by_content_fileshowtype( $content_fileshowtype__id, $order="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtypeitem \n";
	$SQL_QUERY .= "WHERE content_fileshowtype__id = '".sm_secure_string_sql( $content_fileshowtype__id)."' \n";
	switch($order){
		case "name": default:
			$SQL_QUERY .= "ORDER BY content_fileshowtypeitem__name \n";
			break;
		case "sysname": default:
			$SQL_QUERY .= "ORDER BY content_fileshowtypeitem__sysname \n";
			break;
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtypeitem_fetch_by_content_fileshowtype()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

$CONTENT_FILESHOWTYPEITEM_ARRAY=array();
if($result=content_fileshowtypeitem_fetch_all()){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		$CONTENT_FILESHOWTYPEITEM_ARRAY[ $row["content_fileshowtypeitem__sysname"] ] = array(
			"name"  => $row["content_fileshowtypeitem__name"],
			"id" => $row["content_fileshowtypeitem__id"],
			"showtype_sysname" => $CONTENT_FILESHOWTYPE_ARRAY[$row["content_fileshowtype__id"]]["sysname"],
		);
	}
}

?>