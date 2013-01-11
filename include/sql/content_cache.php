<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_cache
 */

/**
 * @category	content_cache
 * @package		sql
 * @version		5.0.0
*/
function content_cache_add( $dane ) {

	$dane = trimall($dane);

	$dane["content_cache__id"] = uuid();

	$dane["record_create_date"] = time();
	$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];

	$dane["content_cache__data"] = base64_encode($dane["content_cache__data"]);
	$dane["content_cache__encode"] = "base64";
	$dane["content_cache__w"] = $dane["content_cache__w"] ? $dane["content_cache__w"] : 0;
	$dane["content_cache__h"] = $dane["content_cache__h"] ? $dane["content_cache__h"] : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_cache VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__table"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__tableid"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__w"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__h"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__ttl"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__contenttype"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__encode"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_cache__data"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_cache_add()",$SQL_QUERY,$e); }
	
	return $dane["content_cache__id"];
}

/**
 * @category	content_cache
 * @package		sql
 * @version		5.0.0
*/
function content_cache_get( $content_cache__table, $content_cache__tableid, $content_cache__w, $content_cache__h ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_cache \n";
	$SQL_QUERY .= "WHERE content_cache__table='". sm_secure_string_sql( $content_cache__table)."' ";
	$SQL_QUERY .= "  AND content_cache__tableid='". sm_secure_string_sql( $content_cache__tableid)."' ";
	$SQL_QUERY .= "  AND content_cache__w='". sm_secure_string_sql( $content_cache__w)."' ";
	$SQL_QUERY .= "  AND content_cache__h='". sm_secure_string_sql( $content_cache__h)."' ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_cache_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_cache
 * @package		sql
 * @version		5.0.0
*/
function content_cache_delete( $content_cache__table, $content_cache__tableid ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_cache \n";
	$SQL_QUERY .= "WHERE content_cache__table='". sm_secure_string_sql( $content_cache__table)."' ";
	$SQL_QUERY .= "  AND content_cache__tableid='". sm_secure_string_sql( $content_cache__tableid)."' ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_cache_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_cache
 * @package		sql
 * @version		5.0.0
*/
function content_cache_clear() {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_cache \n";
	$SQL_QUERY .= "WHERE (record_create_date + content_cache__ttl) < UNIX_TIMESTAMP() ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_cache_clear()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

?>