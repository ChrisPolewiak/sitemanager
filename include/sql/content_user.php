<?
/**
 * content_user
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	content_user
 */

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_add( $dane ) {
	$dane["content_user__id"] = "0";
	return content_user_edit( $dane );
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_user__id"]) {
		$tmp_dane = content_user_dane( $dane["content_user__id"] );
		$dane["content_user__login_correct"] = $tmp_dane["content_user__login_correct"];
		$dane["content_user__login_false"] = $tmp_dane["content_user__login_false"];
		$dane["content_user__login_falsecount"] = $tmp_dane["content_user__login_falsecount"];
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
	}
	else {
		$dane["content_user__id"] = uuid();
		$dane["content_user__login_correct"] = 0;
		$dane["content_user__login_false"] = 0;
		$dane["content_user__login_falsecount"] = 0;
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_user__username"] = $dane["content_user__username"] ? strtolower($dane["content_user__username"]) : $tmp_dane["content_user__username"];
	$dane["content_user__password"] = $dane["content_user__password"] ? crypt($dane["content_user__password"]) : $tmp_dane["content_user__password"];

	$dane["content_user__hide_email"] = $dane["content_user__hide_email"] ? 1 : 0;
	$dane["content_user__confirm_regulation"] = $dane["content_user__confirm_regulation"] ? 1 : 0;
	$dane["content_user__confirm_userdata"] = $dane["content_user__confirm_userdata"] ? 1 : 0;
	$dane["content_user__confirm_marketing"] = $dane["content_user__confirm_marketing"] ? 1 : 0;

	sm_sql_transaction_begin();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_user_base VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__username"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__password"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__surname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__firstname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__email"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__hide_email"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__phone"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__company"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__postcode"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__city"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__street"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__country"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__confirm_regulation"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__confirm_userdata"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__confirm_marketing"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__status"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__comment"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__admin_hostallow"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__login_correct"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__login_false"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__login_falsecount"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_edit:base()",$SQL_QUERY,$e); }

	/*
	 * CodeTrigger Injection
	 */
	sitemanager_codetrigger_exec("post:sitemanager_content_user_edit_phpuser", array(
		"username"=>$dane["content_user__username"],
	));
	
	foreach($dane AS $k=>$v){
		if(preg_match("/^content_user_extra_/", $k)) {
			$fieldnames[]="`".$k."`";
			$fieldvalues[]="'".$v."'";
		}
	}

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_user_extra (\n";
	$SQL_QUERY .= " content_user__id\n ";
	if(is_array($fieldnames)) {
		$SQL_QUERY .= ",";
		$SQL_QUERY .= join(",\n ", $fieldnames)."\n";
	}
	$SQL_QUERY .= ")\n";
	$SQL_QUERY .= "VALUES (\n";
	$SQL_QUERY .= " '". sm_secure_string_sql( $dane["content_user__id"])."'\n ";
	if(is_array($fieldnames)) {
		$SQL_QUERY .= ",";
		$SQL_QUERY .= join(",\n ", $fieldvalues )."\n";
	}
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_edit:extra()",$SQL_QUERY,$e); }

	sm_sql_transaction_commit();

	return $dane["content_user__id"];
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_dane( $content_user__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_get_by_username( $username ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__username LIKE '". sm_secure_string_sql( $username)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_get_by_username()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_get_by_username_new( $username ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__username LIKE '". sm_secure_string_sql( $username)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_get_by_username_new()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_delete( $content_user__id ) {

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_disable( $content_user__id ) {
	return content_user__status_change( $content_user__id, 2 );
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user__status_change( $content_user__id, $content_user__status) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "SET content_user__status='". sm_secure_string_sql( $content_user__status)."' \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user__status_change()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user__password_change( $content_user__id, $password) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "SET content_user__password='". sm_secure_string_sql( $password)."' \n";
	$SQL_QUERY .= "WHERE content_user__id='". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user__password_change()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_login_status_update( $content_user__id, $status ) {

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_user \n";
	if($status) {
		$SQL_QUERY .= "SET content_user__login_correct='".time()."', \n";
		$SQL_QUERY .= "content_user__login_falsecount=0 \n";
	}
	else {
		$SQL_QUERY .= "SET content_user__login_false='".time()."', \n";
		$SQL_QUERY .= "content_user__login_falsecount=content_user__login_falsecount+1  \n";
	}
	$SQL_QUERY .= "WHERE content_user__id = '". sm_secure_string_sql( $content_user__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_login_status_update()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_get_by_email( $email ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__email LIKE '". sm_secure_string_sql( $email)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_get_by_email()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_get_by_id( $content_user__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE content_user__id ='". sm_secure_string_sql( $content_user__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_get_by_id()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_fetch_all() {

	$SQL_QUERY  = "SELECT *, FROM_UNIXTIME(record_create_date) as record_create_date \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_fetch_all_count() {

	$SQL_QUERY  = "SELECT COUNT(*) as ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "ORDER BY content_user__surname, content_user__firstname, content_user__username";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_fetch_all_count()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user__password_check( $password, $prev_password, $content_username ) {
	global $ERROR;

	if ($password==$content_username){
		$ERROR[] = "Hasło nie może być takie jak identyfikator";
	}
	$stronglevel = CheckPasswordStrength($password);
	if ($stronglevel<2) {
		$ERROR[] = "Hasło musi się składać z liter małych, dużych, cyfr lub znaków specjalnych. Musi zawierać jednocześnie litery oraz cyfry.";
	}
	if (strlen($password)<8){
		$ERROR[] = "Hasło musi posiadać min 5 znaków";
	}
}

/**
 * @category	content_user
 * @package		sql
 * @version		5.0.0
*/
function content_user_search( $column, $search ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_user \n";
	$SQL_QUERY .= "WHERE \n";

	switch($column) {
		case "name":
			$SQL_QUERY .= "content_user__firstname LIKE '%". sm_secure_string_sql( $search)."%' OR content_user__surname LIKE '%". sm_secure_string_sql( $search)."%' \n";
			$SQL_QUERY .= "ORDER BY content_user__surname, content_user__firstname \n";
			break;
		case "company":
			$SQL_QUERY .= "content_user__company LIKE '%". sm_secure_string_sql( $search)."%' OR content_user__surname LIKE '%". sm_secure_string_sql( $search)."%' \n";
			$SQL_QUERY .= "ORDER BY content_user__company, content_user__surname \n";
			break;
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_user_search()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

?>