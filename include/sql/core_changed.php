<?
/**
 * core_changed
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_changed
 */

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_add( $core_changed__tableid, $core_changed__table, $core_changed__olddata, $core_changed__state="edit" ) { 

	$core_changed__id = uuid();
	$dane["core_changed__olddata"] = addslashes(json_encode($core_changed__olddata));

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_changed VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_changed__id ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_changed__tableid ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_changed__table ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["core_changed__olddata"] ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_changed__state ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"] ) ."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"] ) ."' \n";
	$SQL_QUERY .= ")\n";
 
	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_add()",$SQL_QUERY,$e); }

	return $core_changed__id;
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_dane( $core_changed__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__id='". sm_secure_string_sql( $core_changed__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_delete_by_state( $core_changed__state, $delay=0 ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__state='". sm_secure_string_sql( $core_changed__state ) ."' \n";
	if( $delay!="" )
		$SQL_QUERY .= "AND record_create_date<". sm_secure_string_sql( $delay )."*86400 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_delete_by_state()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_delete( $core_changed__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__id='". sm_secure_string_sql( $core_changed__id ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_delete_by_id( $core_changed__tableid, $core_changed__table ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__tableid='". sm_secure_string_sql( $core_changed__tableid ) ."' \n";
	$SQL_QUERY .= "  AND core_changed__table='". sm_secure_string_sql( $core_changed__table ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_delete_by_id()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_fetch_by_id( $core_changed__tableid, $core_changed__table, $state="", $date_from="", $date_to="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__tableid = '". sm_secure_string_sql( $core_changed__tableid ) ."' \n";
	$SQL_QUERY .= "  AND core_changed__table = '". sm_secure_string_sql( $core_changed__table ) ."' \n";
	if($state) {
		$SQL_QUERY .= "  AND core_changed__state = '". sm_secure_string_sql($state). "' \n";
	}
	if($date_from) {
		$SQL_QUERY .= "  AND record_create_date >= '".strtotime( sm_secure_string_sql($date_from) )."' \n";
	}
	if($date_to) {
		$SQL_QUERY .= "  AND record_create_date <= '".strtotime( sm_secure_string_sql($date_to) )."' \n";
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_fetch_by_id()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_fetch_by_table( $core_changed__table, $state="", $date_from="", $date_to="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "WHERE core_changed__table = '". sm_secure_string_sql( $core_changed__table ) ."' \n";
	if($state) {
		$SQL_QUERY .= "  AND core_changed__state = '". sm_secure_string_sql($state). "' \n";
	}
	if($date_from) {
		$SQL_QUERY .= "  AND record_create_date >= '".strtotime( sm_secure_string_sql($date_from) )."' \n";
	}
	if($date_to) {
		$SQL_QUERY .= "  AND record_create_date <= '".strtotime( sm_secure_string_sql($date_to) )."' \n";
	}
	$SQL_QUERY .= "ORDER BY record_create_date \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_fetch_by_table()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_changed \n";
	$SQL_QUERY .= "ORDER BY record_create_date \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_changed
 * @package		sql
 * @version		5.0.0
*/
function core_changed_count_by_state( $core_changed__state ) {

	$SQL_QUERY  = "SELECT COUNT(*) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_changed \n";
	if( $core_changed__state!="" )
	{
		$SQL_QUERY .= "WHERE core_changed__state='". sm_secure_string_sql( $core_changed__state )."'\n";
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_changed_count_by_state()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

?>