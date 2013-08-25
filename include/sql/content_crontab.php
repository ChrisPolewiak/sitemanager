<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_crontab
 */

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_add( $dane ) {
	$dane["content_crontab__id"] = "0";
	return content_crontab_edit( $dane );
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_crontab__id"]) {
		$tmp_dane = content_crontab_dane( $dane["content_crontab__id"] );
		$dane["content_crontab__lastrunat"] = $tmp_dane["content_crontab__lastrunat"];
		$dane["content_crontab__laststatus"] = $tmp_dane["content_crontab__laststatus"];
		$dane["content_crontab__lastmessage"] = $tmp_dane["content_crontab__lastmessage"];
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_crontab__id"], "content_crontab", $tmp_dane, "edit" );
	}
	else {
		$dane["content_crontab__id"] = uuid();
		$dane["content_crontab__lastrunat"] = "0000-00-00";
		$dane["content_crontab__laststatus"] = "0";
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_crontab__id"], "content_crontab", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_crontab__active"] = $dane["content_crontab__active"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_crontab VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__active"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__mhdmd"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__exec"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__lastrunat"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__laststatus"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_crontab__lastmessage"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_edit()",$SQL_QUERY,$e); }

	return $dane["content_crontab__id"];
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_delete( $content_crontab__id ) {

	if ($deleted = content_crontab_dane( $content_crontab__id ) ) {
		core_changed_add( $content_crontab__id, "content_crontab", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "WHERE content_crontab__id='". sm_secure_string_sql( $content_crontab__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_dane( $content_crontab__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "WHERE content_crontab__id='". sm_secure_string_sql( $content_crontab__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "ORDER BY content_crontab__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_fetch_active() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "WHERE content_crontab__active != 0 \n";
	$SQL_QUERY .= "ORDER BY content_crontab__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_fetch_active()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_updaterunat( $content_crontab__id ) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "SET content_crontab__lastrunat=".time().", content_crontab__laststatus=0, content_crontab__lastmessage=''  \n";
	$SQL_QUERY .= "WHERE content_crontab__id='". sm_secure_string_sql( $content_crontab__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_updaterunat()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_crontab
 * @package		sql
 * @version		5.0.0
*/
function content_crontab_updatestatus( $content_crontab__id, $content_crontab__laststatus, $content_crontab__lastmessage ) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_crontab \n";
	$SQL_QUERY .= "SET content_crontab__laststatus='". sm_secure_string_sql( $content_crontab__laststatus)."', \n";
	$SQL_QUERY .= "    content_crontab__lastmessage='". sm_secure_string_sql( $content_crontab__lastmessage)."' \n";
	$SQL_QUERY .= "WHERE content_crontab__id='". sm_secure_string_sql( $content_crontab__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_crontab_updatestatus()",$SQL_QUERY,$e); }

	return 1;
}

?>