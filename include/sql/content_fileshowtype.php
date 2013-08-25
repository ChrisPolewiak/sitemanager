<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_fileshowtype
 */

/**
 * @category	content_fileshowtype
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtype_add( $dane ) {
	$dane["content_fileshowtype__id"] = "0";
	return content_fileshowtype_edit( $dane );
}

/**
 * @category	content_fileshowtype
 * @package		sql
 * @version		5.0.1
*/
function content_fileshowtype_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_fileshowtype__id"]) {
		$tmp_dane = content_fileshowtype_dane( $dane["content_fileshowtype__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_fileshowtype__id"], "content_fileshowtype", $tmp_dane, "edit" );
	}
	else {
		$dane["content_fileshowtype__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_fileshowtype__id"], "content_fileshowtype", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_fileshowtype VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_fileshowtype__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_fileshowtype__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_fileshowtype__sysname"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtype_edit()",$SQL_QUERY,$e); }

	return $dane["content_fileshowtype__id"];
}

/**
 * @category	content_fileshowtype
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtype_delete( $content_fileshowtype__id ) {

	if ($deleted = content_fileshowtype_dane( $content_fileshowtype__id ) ) {
		core_changed_add( $content_fileshowtype__id, "content_fileshowtype", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_fileshowtype \n";
	$SQL_QUERY .= "WHERE content_fileshowtype__id='". sm_secure_string_sql( $content_fileshowtype__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtype_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_fileshowtype
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtype_dane( $content_fileshowtype__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtype \n";
	$SQL_QUERY .= "WHERE content_fileshowtype__id='". sm_secure_string_sql( $content_fileshowtype__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtype_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_fileshowtype
 * @package		sql
 * @version		5.0.0
*/
function content_fileshowtype_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_fileshowtype \n";
	$SQL_QUERY .= "ORDER BY content_fileshowtype__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_fileshowtype_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

$CONTENT_FILESHOWTYPE_ARRAY=array();
if($result=content_fileshowtype_fetch_all()){
	while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
		$CONTENT_FILESHOWTYPE_ARRAY[ $row["content_fileshowtype__id"] ] = array(
			"name"  => $row["content_fileshowtype__name"],
			"sysname" => $row["content_fileshowtype__sysname"],
		);
	}
}


?>