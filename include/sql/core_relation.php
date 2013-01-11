<?
/**
 * core_relation
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_relation
 */

/**
 * @category	core_relation
 * @package		sql
 * @version		5.0.0
*/
function corerelation_edit( $core_relation__srctable, $core_relation__srcid, $core_relation__dsttable, $core_relation__dstid, $core_relation__order, $core_relation__type ) {

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"]   = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_relation VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__srctable)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__srcid)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__dsttable)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__dstid)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__order)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $core_relation__type)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("corerelation_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	core_relation
 * @package		sql
 * @version		5.0.0
*/
function corerelation_fetch_ids_by_src( $core_relation__srctable, $core_relation__srcid ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_relation \n";
	$SQL_QUERY .= "WHERE core_relation__srctable='". sm_secure_string_sql( $core_relation__srctable)."' \n";
	$SQL_QUERY .= "  AND core_relation__srcid='". sm_secure_string_sql( $core_relation__srcid)."' \n";
	$SQL_QUERY .= "ORDER BY core_relation__dsttable,core_relation__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("corerelation_fetch_ids_by_src()",$SQL_QUERY,$e); }

	return $result->rowCount()>0?$result:0;
}

/**
 * @category	core_relation
 * @package		sql
 * @version		5.0.0
*/
function corerelation_fetch_ids_by_dst( $core_relation__dsttable, $core_relation__dstid ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_relation \n";
	$SQL_QUERY .= "WHERE core_relation__dsttable='". sm_secure_string_sql( $core_relation__dsttable)."' \n";
	$SQL_QUERY .= "  AND core_relation__dstid='". sm_secure_string_sql( $core_relation__dstid)."' \n";
	$SQL_QUERY .= "ORDER BY core_relation__srctable,core_relation__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("corerelation_fetch_ids_by_dst()",$SQL_QUERY,$e); }

	return $result->rowCount()>0?$result:0;
}

/**
 * @category	core_relation
 * @package		sql
 * @version		5.0.0
*/
function corerelation_fetch_by_src( $core_relation__srctable, $core_relation__srcid, $dsttable ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_relation AS relation \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_". sm_secure_string_sql( $dsttable)." AS dsttable \n";
	$SQL_QUERY .= "       ON dsttable.id_". sm_secure_string_sql( $dsttable)." = relation.core_relation__dstid \n";
	$SQL_QUERY .= "WHERE core_relation__srctable='". sm_secure_string_sql( $core_relation__srctable)."' \n";
	$SQL_QUERY .= "  AND core_relation__srcid='". sm_secure_string_sql( $core_relation__srcid)."' \n";
	$SQL_QUERY .= "ORDER BY core_relation__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("corerelation_fetch_by_src()",$SQL_QUERY,$e); }

	return $result->rowCount()>0?$result:0;
}

/**
 * @category	core_relation
 * @package		sql
 * @version		5.0.0
*/
function corerelation_fetch_by_dst( $core_relation__dsttable, $core_relation__dstid, $srctable ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_relation AS relation \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_". sm_secure_string_sql( $srctable)." AS srctable \n";
	$SQL_QUERY .= "       ON srctable.id_". sm_secure_string_sql( $srctable)." = relation.core_relation__srcid \n";
	$SQL_QUERY .= "WHERE core_relation__dsttable='". sm_secure_string_sql( $core_relation__dsttable)."' \n";
	$SQL_QUERY .= "  AND core_relation__dstid='". sm_secure_string_sql( $core_relation__dstid)."' \n";
	$SQL_QUERY .= "ORDER BY core_relation__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("corerelation_fetch_by_dst()",$SQL_QUERY,$e); }

	return $result->rowCount()>0?$result:0;
}

?>