<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_extra
 */

$CONTENT_EXTRA_DBTYPE_ARRAY = array(
	1 => array("name"=>"text","title"=>"ciąg znaków powyżej 256"),
	2 => array("name"=>"varchar(256)","title"=>"ciąg znaków do 256"),
	3 => array("name"=>"varchar(128)","title"=>"ciąg znaków do 128"),
	4 => array("name"=>"varchar(64)","title"=>"ciąg znaków do 64"),
	5 => array("name"=>"int","title"=>"Dowolna Liczba"),
	6 => array("name"=>"tinyint","title"=>"Liczba nie większa niż 256"),
	7 => array("name"=>"date","title"=>"Data"),
	8 => array("name"=>"datetime","title"=>"Data i godzina"),
);

$CONTENT_EXTRA_INPUT_ARRAY = array(
	1 => array("name"=>"text","title"=>"pole tekstowe - 1 wiersz"),
	2 => array("name"=>"textarea","title"=>"pole tekstowe - wiele wierszy"),
	3 => array("name"=>"select","title"=>"lista rozwijana"),
	4 => array("name"=>"checkbox","title"=>"pole wyboru"),
	5 => array("name"=>"relation","title"=>"relacja"),
);

$CONTENT_EXTRA_OBJECT_ARRAY = array(
	1 => array("name"=>"content_user","title"=>"Baza użytkowników"),
);

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_add( $dane ) {
	$dane["content_extra__id"] = "0";
	return content_extra_edit( $dane );
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_edit( $dane ) {

	$dane = trimall($dane);

	if ($dane["content_extra__id"]) {
		$tmp_dane = content_extra_dane( $dane["content_extra__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["record_create_id"];
		core_changed_add( $dane["content_extra__id"], "content_extra", $tmp_dane, "edit" );
	}
	else {
		$dane["content_extra__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_extra__id"], "content_extra", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_extra__required"] = $dane["content_extra__required"] ? 1 : 0;
	$dane["content_extra__listview"] = $dane["content_extra__listview"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_extra VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__object"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__dbname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__dbtype"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__info"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__input"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__required"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__listview"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__relationtable"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__relationname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_extra__relationfunction"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_edit()",$SQL_QUERY,$e); }

	return $dane["content_extra__id"];
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_delete( $content_extra__id ) {

	if ($deleted = content_extra_dane( $content_extra__id ) ) {
		core_changed_add( $content_extra__id, "content_extra", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_extra \n";
	$SQL_QUERY .= "WHERE content_extra__id='". sm_secure_string_sql( $content_extra__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_dane( $content_extra__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_extra \n";
	$SQL_QUERY .= "WHERE content_extra__id='". sm_secure_string_sql( $content_extra__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_fetch_by_object( $object ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_extra \n";
	$SQL_QUERY .= "WHERE content_extra__object='". sm_secure_string_sql( $object)."'";
	$SQL_QUERY .= "ORDER BY content_extra__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_fetch_by_object()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_extra \n";
	$SQL_QUERY .= "ORDER BY content_extra__name";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_modify_add_field( $table, $fieldname, $fieldtype ) {

	$SQL_QUERY  = "ALTER TABLE ".DB_TABLEPREFIX."_".$table." ADD COLUMN $fieldname $fieldtype";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_add_field()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_modify_edit_field( $table, $prev_fieldname, $fieldname, $fieldtype ) {

	$dbprefix = $object."_extra";
	$SQL_QUERY  = "ALTER TABLE ".DB_TABLEPREFIX."_".$table." CHANGE COLUMN $prev_fieldname $fieldname $fieldtype";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_edit_field()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_modify_delete_field( $table, $prev_fieldname ) {

	$dbprefix = $object."_extra";
	$SQL_QUERY  = "ALTER TABLE ".DB_TABLEPREFIX."_".$table." DROP COLUMN $prev_fieldname";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_delete_field()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_extra
 * @package		sql
 * @version		5.0.0
*/
function content_extra_modify_db( $dane, $prev_dane, $action ) {
	global $ERROR;

	$object = $dane["content_extra__object"] ? $dane["content_extra__object"] : $prev_dane["content_extra__object"];
	$new_fieldname = $dane["content_extra__dbname"];
	$prev_fieldname = $prev_dane["content_extra__dbname"];
	$fielddbtype = $dane["content_extra__dbtype"];
	$dbprefix = $object."_extra";

	$SQL_QUERY = "DESC ".DB_TABLEPREFIX."_".$object."_extra; ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_db:desc1()",$SQL_QUERY,$e); }

	while($row=$result->fetch(PDO::FETCH_ASSOC)) {
		$dbfields[ $row["Field"] ] = $row;
	}

	$table = $object."_extra";
	$new_db_fieldname = $dbprefix."_".$new_fieldname;
	$prev_db_fieldname = $dbprefix."_".$prev_fieldname;

	if($action == "add") {
		if( $dbfields[ $new_db_fieldname ] ) {
			$ERROR[] = "W tabeli '$table' istnieje już pole o nazwie '$new_db_fieldname'";
		}
		else {
			content_extra_modify_add_field( $table, $new_db_fieldname, $fielddbtype );
		}
	}
	if($action == "edit") {
		if( $dbfields[ $prev_db_fieldname ] ) {
			content_extra_modify_edit_field( $table, $prev_db_fieldname, $new_db_fieldname, $fielddbtype );
		}
		else {
			content_extra_modify_add_field( $table, $new_db_fieldname, $fielddbtype );
		}
	}
	elseif($action == "delete") {
		if( $dbfields[ $prev_db_fieldname ] ) {
			content_extra_modify_delete_field( $table, $prev_db_fieldname, $fielddbtype );
		}
		else {
			$ERROR[] = "W tabeli '$table' nie istnieje pole o nazwie '$prev_db_fieldname'";
		}
	}

	// kontrola zawartosci bazy
	$SQL_QUERY = "DESC ".DB_TABLEPREFIX."_".$object."_extra; ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_db:desc2()",$SQL_QUERY,$e); }

	while($row=$result->fetch(PDO::FETCH_ASSOC)) {
		$dbfields[ $row["Field"] ] = $row;
	}

	if($result = content_extra_fetch_all()){
		$extrafieldname = $object."_extra_".$k;
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$extrafields[ $row["content_extra__object"] ][ $extrafieldname.$row["content_extra__dbname"] ] =1;
		}
		
		// sprawdzam czy wszystkie pola z definicji sa w bazie danych
		foreach($extrafields[$object] AS $extrafield=>$null) {
#			echo "extrafield: $extrafield<br>";
			if(! $dbfields[$extrafield] ) {
				$ERROR[] = "Błąd systemu: brak w tabeli '$table' pola '$extrafield'";
			}
		}
		// sprawdzam czy wszystkie pola z bazy danych są w definicjach
		foreach($dbfields AS $dbfield=>$null) {
#			echo "dbfield: $dbfield ($prev_fieldname)<br>";
			if(! $extrafields[$object][$dbfield] && $dbfield!=$object."__id" && $dbfield!=$object."_extra_".$prev_fieldname) {
				$ERROR[] = "Błąd systemu: w tabeli '$table' jest nadmiarowe pole '$dbfield'";
			}
		}
	}

	// create view
	$SQL_QUERY  = "DROP VIEW IF EXISTS sm_$object";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_db:drop_view()",$SQL_QUERY,$e); }

	$SQL_QUERY  = "CREATE ALGORITHM=MERGE VIEW ".DB_TABLEPREFIX."_".$object." AS \n";
	$SQL_QUERY .= "SELECT ".$object."_base.*";
	foreach($extrafields[$object] AS $extrafield=>$null) {
		$SQL_QUERY .= ",\n";
		$SQL_QUERY .= "   ".$object."_extra.$extrafield";
	}
	$SQL_QUERY .= "\n";
	$SQL_QUERY .= "FROM (".DB_TABLEPREFIX."_".$object."_base AS ".$object."_base, ".DB_TABLEPREFIX."_".$object."_extra AS ".$object."_extra) \n";
	$SQL_QUERY .= "WHERE (".$object."_extra.".$object."__id=".$object."_base.".$object."__id) \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_extra_modify_db:create_view()",$SQL_QUERY,$e); }
	
	return (is_array($ERROR) ? 0 : 1);
}

?>