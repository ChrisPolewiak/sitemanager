<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	core_session
 */

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.1
*/
function core_session_dane($core_session__sid) {
	global $SESSION_LIFE;

	core_session_delete_old();

	$SQL_QUERY  = "SELECT AES_DECRYPT(core_session__data,'".SM_DATA_ENCRYPTION_KEY."') AS core_session__data \n";
#	$SQL_QUERY  = "SELECT core_session__data \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_session \n";
	$SQL_QUERY .= "WHERE core_session__sid = '".$core_session__sid."' \n";
	$SQL_QUERY .= "  AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(core_session__lastused) < ".$SESSION_LIFE." \n";
//	$SQL_QUERY .= "  AND core_session__remoteaddr = '".$_SERVER["REMOTE_ADDR"]."' \n";
//	$SQL_QUERY .= "  AND core_session__useragent = '".sm_secure_string_sql($_SERVER["HTTP_USER_AGENT"])."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
		return $row["core_session__data"];
	}
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.1
*/
function core_session_edit($core_session__sid, $SESSION) {
	global $DB_ENGINE,$DB_NAME,$DB_SERVER,$DB_USER,$DB_PASS;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_core_session VALUES ( \n";
	$SQL_QUERY .= "'".$core_session__sid."', \n";
	$SQL_QUERY .= "AES_ENCRYPT('".$SESSION."','".SM_DATA_ENCRYPTION_KEY."'), \n";
	$SQL_QUERY .= "'".$_SERVER["REMOTE_ADDR"]."', \n";
	$SQL_QUERY .= "'".sm_secure_string_sql($_SERVER["HTTP_USER_AGENT"])."', \n";
	$SQL_QUERY .= "NULL)\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.0
*/
function core_session_delete($core_session__sid) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_session \n";
	$SQL_QUERY .= "WHERE core_session__sid = '".$core_session__sid."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.0
*/
function core_session_delete_old() {
	global $SESSION_LIFE;

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_core_session \n";
	$SQL_QUERY .= "WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(core_session__lastused) > ".$SESSION_LIFE." \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.0
*/
function core_session_count() {
	global $REMOTE_ADDR;

	$SQL_QUERY  = "SELECT core_session__sid \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_session \n";
	$SQL_QUERY .= "WHERE core_session__remoteaddr = '".$REMOTE_ADDR."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.0
*/
function core_session_fetch_all() {

	$SQL_QUERY = "SELECT * FROM ".DB_TABLEPREFIX."_core_session \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	core_session
 * @package		sql
 * @version		5.0.0
*/
function core_session_count_all() {

	$SQL_QUERY  = "SELECT core_session__sid \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_core_session\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_tags_fetch_all()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

?>
