<?
/**
 * content_peeklist
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_peeklist
 */

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_add( $dane ) {
	$dane["content_peeklist__id"] = "0";
	return content_peeklist_edit( $dane );
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.1
*/
function content_peeklist_edit( $dane ) {
	$dane = trimall($dane);

	if ($dane["content_peeklist__id"]) {
		$tmp_dane = content_peeklist_dane( $dane["content_peeklist__id"] );
		$dane["record_create_date"] = $tmp_dane["record_create_date"];
		$dane["record_create_id"]   = $tmp_dane["content_peeklist__id"];
		core_changed_add( $dane["content_peeklist__id"], "content_peeklist", $tmp_dane, "edit" );
	}
	else {
		$dane["content_peeklist__id"] = uuid();
		$dane["record_create_date"] = time();
		$dane["record_create_id"]   = $_SESSION["content_user"]["content_user__id"];
		core_changed_add( $dane["content_peeklist__id"], "content_peeklist", "", "add" );
	}

	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_peeklist__published"] = $dane["content_peeklist__published"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_peeklist VALUES (\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__plugin"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__name"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__sysname"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle01"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle02"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle03"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle04"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle05"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle06"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle07"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle08"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle09"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_peeklist__vtitle10"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."',\n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."'\n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_edit()",$SQL_QUERY,$e); }

	return $dane["content_peeklist__id"];
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_delete( $content_peeklist__id ) {

	if ($deleted = content_peeklist_dane( $content_peeklist__id ) ) {
		core_changed_add( $content_peeklist__id, "content_peeklist", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "WHERE content_peeklist__id='". sm_secure_string_sql( $content_peeklist__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_delete()",$SQL_QUERY,$e); }

	return $result->rowCount();
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_dane( $content_peeklist__id, $all="" ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "WHERE content_peeklist__id='". sm_secure_string_sql( $content_peeklist__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_dane()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_get_by_plugin( $content_peeklist__plugin ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "WHERE content_peeklist__plugin='". sm_secure_string_sql( $content_peeklist__plugin)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_get_by_plugin()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_get_by_name( $content_peeklist__plugin, $content_peeklist__sysname ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "WHERE content_peeklist__plugin='". sm_secure_string_sql( $content_peeklist__plugin)."'";
	$SQL_QUERY .= "  AND content_peeklist__sysname='". sm_secure_string_sql( $content_peeklist__sysname)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_get_by_name()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_get( $content_peeklist__id ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "WHERE content_peeklist__id='". sm_secure_string_sql( $content_peeklist__id)."'";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_fetch_all($all="", $limit=0, $max="") {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_peeklist \n";
	$SQL_QUERY .= "ORDER BY content_peeklist__name \n";
	
	if($limit != 0 || $max != ""){
		$SQL_QUERY .= "LIMIT ". sm_secure_string_sql( $limit).", ". sm_secure_string_sql( $max);
	}

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_peeklist_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_peeklist
 * @package		sql
 * @version		5.0.0
*/
function content_peeklist_rebuild( $content_peeklist__id ) {
	global $ROOT_DIR;

	if ( $content_peeklist = content_peeklist_get( $content_peeklist__id ) ) {
		$filenamestr = strtolower($content_peeklist["content_peeklist__plugin"])."-".strtolower($content_peeklist["content_peeklist__sysname"]);
		$varstr = strtoupper($content_peeklist["content_peeklist__plugin"])."_".strtoupper($content_peeklist["content_peeklist__sysname"])."_ARRAY";
		$fp = fopen($ROOT_DIR."/cache/".$filenamestr."_tmp.php","w") or error("nie moge otworzyc - ".$ROOT_DIR."/cache/".$db."_tmp.php");
		fputs($fp, "<"."?php\n# include file\n");
		fputs($fp, "# DON'T EDIT\n");
		fputs($fp, "#\n");
		fputs($fp, "# ".$filenamestr."\n");
		fputs($fp, "#\n");
		fputs($fp, "\n");
		if ( $result = content_peeklistitem_fetch_by_content_peeklist( $content_peeklist__id ) ) {
			while ( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
				fputs($fp, "\$" . $varstr . "[\"" . $row["content_peeklistitem__id"] . "\"] = array( ");
				for($i=1;$i<=10;$i++){
					$num=$i<10?"0".$i:$i;
					fputs($fp, " \"" . $num . "\"=>\"" . $row["content_peeklistitem__value".$num]."\",");
				}
				fputs($fp, ");\n");
			}
		}
		fputs($fp, "\n");
		fputs($fp, "?".">");
		fclose($fp);
		if (file_exists($ROOT_DIR."/cache/".$filenamestr.".php")){
			unlink($ROOT_DIR."/cache/".$filenamestr.".php");
		}
		rename($ROOT_DIR."/cache/".$filenamestr."_tmp.php", $ROOT_DIR."/cache/".$filenamestr.".php"); 
		return 1;
	}
}

?>