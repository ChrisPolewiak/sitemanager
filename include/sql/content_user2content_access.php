<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @package		sql
 * @category	content_user2content_access
 */

/**
 * @category	content_user2content_access
 * @package		sql
 * @version		5.1.0
*/
function content_user2content_access_add( $content_user2content_access__id, $content_access__id, $content_user__id, $content_user2content_access__bit ) {

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$content_user2content_access__id = $content_user2content_access__id ? $content_user2content_access__id : uuid();

	if ($content_access__id && $content_user__id && $content_user2content_access__bit) {
		$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_user2content_access \n";
		$SQL_QUERY .= "VALUES ( \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $content_user2content_access__id)."', \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $content_access__id)."', \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $content_user__id)."', \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $content_user2content_access__bit)."', \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
		$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."'\n";
		$SQL_QUERY .= ")";

		try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_access_add()",$SQL_QUERY,$e); }

 		return $content_access__id;
	}
}

/**
 * @category	content_user2content_access
 * @package		sql
 * @version		5.1.0
*/
function content_user2content_access_delete( $content_user2content_access__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user2content_access \n";
	$SQL_QUERY .= "WHERE content_user2content_access__id='". sm_secure_string_sql( $content_user2content_access__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_access_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_user2content_access
 * @package		sql
 * @version		5.1.0
*/
function content_user2content_access_delete_by_user( $content_user__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user2content_access \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_access_delete_by_user()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_user2content_access
 * @package		sql
 * @version		5.1.0
*/
function content_user2content_access_delete_by_access( $content_access__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user2content_access \n";
	$SQL_QUERY .= "WHERE content_access__id='". sm_secure_string_sql( $content_access__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_access_delete_by_access()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_user2content_access
 * @package		sql
 * @version		5.1.0
*/
function content_user2content_access_fetch_by_user( $content_user__id ) {

	$SQL_QUERY  = "SELECT access.*,acl.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_access AS access, ".DB_TABLEPREFIX."_content_user2content_access AS acl \n";
	$SQL_QUERY .= "WHERE access.content_access__id=acl.content_access__id \n";
	if( is_array($content_user__id) ) {
		$SQL_QUERY .= " AND acl.content_user__id IN ('" . join("','",$content_user__id) . "') \n";
	}
	else {
		$SQL_QUERY .= " AND acl.content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user2content_access_fetch_by_user()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>