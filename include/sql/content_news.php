<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_news
 */

// dodanie nazwy obiektów do których można przypisać pliki
$CONTENT_FILESSHOWTYPE_AVAILABLEOBJECT[] = array("sysname"=>"content_news","name"=>"Wiadomości");

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_add( $dane ) {
	$dane["content_news__id"] = "0";
	return content_news_edit( $dane );
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.1
*/
function content_news_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_news__id"]) {
		$tmp_dane = content_news_dane( $dane["content_news__id"] );
		$dane["record_create_date"] = $tmp_dane["content_news__id"];
		$dane["record_create_id"]   = $tmp_dane["content_news__id"];
		core_changed_add( $dane["content_news__id"], "content_news", $tmp_dane, "edit" );
	}
	else {
		$dane["content_news__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_news__id"], "content_news", $tmp_dane="", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_news__published"] = $dane["content_news__published"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_news VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__datetime"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__published"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__title"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__lead"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__body"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_news__lang"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_edit()",$SQL_QUERY,$e); }

	return $dane["content_news__id"];
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_delete( $content_news__id ) {

	if ($deleted = content_news_dane( $content_news__id ) ) {
		core_changed_add( $content_news__id, "content_news", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_news \n";
	$SQL_QUERY .= "WHERE content_news__id='". sm_secure_string_sql( $content_news__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_dane( $content_news__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news \n";
	$SQL_QUERY .= "WHERE content_news__id='". sm_secure_string_sql( $content_news__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_get( $content_news__id ) {

	$SQL_QUERY  = "SELECT *, UNIX_TIMESTAMP(content_news__datetime) AS timestamp \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news \n";
	$SQL_QUERY .= "WHERE content_news__id='". sm_secure_string_sql( $content_news__id)."'";
	$SQL_QUERY .= "AND content_news__published=1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_fetch_by_date( $datestart, $dateend, $content_newsgroup__id ) {

	$SQL_QUERY  = "SELECT n.*, UNIX_TIMESTAMP(n.content_news__datetime) AS timestamp \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news AS n, ".DB_TABLEPREFIX."_content_news2content_newsgroup AS g\n";
	$SQL_QUERY .= "WHERE g.content_news__id=n.content_news__id \n";
	$SQL_QUERY .= "  AND n.content_news__datetime>='". sm_secure_string_sql( $datestart)."' \n";
	$SQL_QUERY .= "  AND n.content_news__datetime<='". sm_secure_string_sql( $dateend)."' \n";
	if($content_newsgroup__id) {
		$SQL_QUERY .= "  AND g.content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."' \n";
	}
	$SQL_QUERY .= "  AND n.content_news__published=1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_fetch_by_date()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_fetch_by_lang( $content_news__lang, $content_newsgroup__id ) {

	$SQL_QUERY  = "SELECT n.* \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news AS n, ".DB_TABLEPREFIX."_content_news2content_newsgroup AS g\n";
	$SQL_QUERY .= "WHERE g.content_news__id=n.content_news__id \n";
	$SQL_QUERY .= "  AND n.content_news__lang='". sm_secure_string_sql( $content_news__lang)."' \n";
	if($content_newsgroup__id) {
		$SQL_QUERY .= "  AND g.content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."' \n";
	}
	$SQL_QUERY .= "  AND n.content_news__published=1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_fetch_by_lang()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_fetch_by_content_newsgroup( $content_newsgroup__id, $all="", $limit=0, $max=10 ) {

	$SQL_QUERY  = "SELECT *, UNIX_TIMESTAMP(n.content_news__datetime) AS timestamp \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news AS n, ".DB_TABLEPREFIX."_content_news2content_newsgroup AS g\n";
	$SQL_QUERY .= "WHERE g.content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."' \n";
	$SQL_QUERY .= "  AND n.content_news__id=g.content_news__id \n";
	if (!$all) {
		$SQL_QUERY .= "   AND content_news__published=1 \n";
	}
	$SQL_QUERY .= "ORDER BY content_news__datetime DESC, content_news__title \n";
	$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $limit).", ". sm_secure_string_sql( $max);

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_fetch_by_content_newsgroup()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_fetch_by_content_newsgroup_new( $content_newsgroup__id, $all="", $limit=0, $max=10 ) {

	$SQL_QUERY  = "SELECT *, UNIX_TIMESTAMP(n.content_news__datetime) AS timestamp \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news AS n, ".DB_TABLEPREFIX."_content_news2content_newsgroup AS g\n";
	$SQL_QUERY .= "WHERE g.content_newsgroup__id='". sm_secure_string_sql( $content_newsgroup__id)."' \n";
	$SQL_QUERY .= "  AND g.content_news__id=n.content_news__id \n";
	if (!$all) {
		$SQL_QUERY .= "   AND content_news__published=1 \n";
	}
	$SQL_QUERY .= "ORDER BY content_news__datetime DESC, content_news__title \n";
	$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $limit).", ". sm_secure_string_sql( $max);

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_fetch_by_content_newsgroup_new()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_fetch_all($all="", $limit=0, $max="") {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news \n";
	if (!$all) {
		$SQL_QUERY .= "WHERE content_news__published=1 \n";
	}
	$SQL_QUERY .= "ORDER BY content_news__datetime DESC, content_news__title \n";
	
	if($limit != 0 || $max != ""){
		$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $limit).", ". sm_secure_string_sql( $max);
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_news
 * @package		sql
 * @version		5.0.0
*/
function content_news_count() {
	$SQL_QUERY  = "SELECT count(*) AS news_count \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_news \n";
	$SQL_QUERY .= "WHERE content_news__published = 1";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_news_count()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_news_count"];
	}
}

?>