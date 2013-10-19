<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_news2content_newsgroup
 */

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.1.0
*/
function content_news2content_newsgroup_edit( $content_news2content_newsgroup__id, $content_news__id, $content_newsgroup__id ) {

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$content_news2content_newsgroup__id = $content_news2content_newsgroup__id ? $content_news2content_newsgroup__id : uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_news2content_newsgroup VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_news2content_newsgroup__id)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_news__id)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_newsgroup__id)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_edit()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_news2content_newsgroup_fetch_by_content_news( $content_news__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news2content_newsgroup \n";
	$SQL_QUERY .= "WHERE content_news__id='". sm_secure_string_sql( $content_news__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_fetch_by_content_news()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.1.0
*/
function content_news2content_newsgroup_delete( $content_news2content_newsgroup__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_news2content_newsgroup \n";
	$SQL_QUERY .= "WHERE content_news2content_newsgroup__id='". sm_secure_string_sql( $content_news2content_newsgroup__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_news2content_newsgroup_delete_by_content_news( $content_news__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_news2content_newsgroup \n";
	$SQL_QUERY .= "WHERE content_news__id='". sm_secure_string_sql( $content_news__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_delete_by_content_news()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_news2content_newsgroup_fetch_all() {

	$SQL_QUERY  = "SELECT  * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news2content_newsgroup as cn2g\n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_news AS cn ON cn.content_news__id=cn2g.content_news__id\n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_newsgroup AS cng ON cng.content_newsgroup__id=cn2g.content_newsgroup__id\n";
	$SQL_QUERY .= "ORDER BY content_newsgroup__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news2content_newsgroup
 * @package		sql
 * @version		5.0.0
*/
function content_news2content_newsgroup_fetch_all_group( $content_newsgroup__id="" ) {

	$SQL_QUERY  = "SELECT *, cn.content_news__id \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news AS cn \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_news2content_newsgroup as cn2g ON cn.content_news__id=cn2g.content_news__id \n";
	$SQL_QUERY .= "LEFT JOIN ".DB_TABLEPREFIX."_content_newsgroup AS cng ON cng.content_newsgroup__id=cn2g.content_newsgroup__id\n";
	if( $content_newsgroup__id ) {
		$SQL_QUERY .= "WHERE cng.content_newsgroup__id = '". sm_secure_string_sql( $content_newsgroup__id)."' ";
	}
	else {
		$SQL_QUERY .= "GROUP BY cn.content_news__id ";
	}
	$SQL_QUERY .= "ORDER BY content_newsgroup__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news2content_newsgroup_fetch_all_group()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>