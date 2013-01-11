<?
/**
 * core_deleted
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_deleted
 */

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_add( $core_deleted__tableid, $core_deleted__table, $core_deleted__olddata ) { 

	$core_deleted__id = uuid();
	$dane["core_deleted__olddata"] = addslashes(json_encode($core_deleted__olddata));

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_deleted VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_deleted__id ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_deleted__tableid ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_deleted__table ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_deleted__olddata"] ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"] ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"] ) ."' \n";
	$SQL_QUERY .= ")\n";
 
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_add()",$SQL_QUERY,$e); }

	return $core_deleted__id;
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_dane( $core_deleted__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_deleted \n";
	$SQL_QUERY .= "WHERE core_deleted__id='". sm_secure_string_sql( $core_deleted__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_delete( $core_deleted__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_deleted \n";
	$SQL_QUERY .= "WHERE core_deleted__id='". sm_secure_string_sql( $core_deleted__id ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_delete_by_id( $core_deleted__tableid, $core_deleted__table ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_deleted \n";
	$SQL_QUERY .= "WHERE core_deleted__tableid='". sm_secure_string_sql( $core_deleted__tableid ) ."' \n";
	$SQL_QUERY .= "  AND core_deleted__table='". sm_secure_string_sql( $core_deleted__table ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_delete_by_id()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_fetch_by_id( $core_deleted__tableid, $core_deleted__table ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_deleted \n";
	$SQL_QUERY .= "WHERE core_deleted__tableid = '". sm_secure_string_sql( $core_deleted__tableid ) ."' \n";
	$SQL_QUERY .= "  AND core_deleted__table = '". sm_secure_string_sql( $core_deleted__table ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_fetch_by_id()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_fetch_by_table( $core_deleted__table ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_deleted \n";
	$SQL_QUERY .= "WHERE core_deleted__table = '". sm_secure_string_sql( $core_deleted__table ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_fetch_by_table()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_deleted
 * @package		sql
 * @version		5.0.0
*/
function core_deleted_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_deleted \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_deleted_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>