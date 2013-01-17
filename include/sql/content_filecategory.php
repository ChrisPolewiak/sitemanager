<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_filecategory
 */

if (is_file($ROOT_DIR."/include/genfiles/content_filecategory.inc")) {
	include $ROOT_DIR."/include/genfiles/content_filecategory.inc";
}
if (is_file($ROOT_DIR."/include/genfiles/content_filecategory.xml")) {
	$XML_CONTENT_FILECATEGORY = simplexml_load_file($ROOT_DIR."/include/genfiles/content_filecategory.xml");
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_get_info( $content_filecategory__id ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$content_filecategory__id =  sm_secure_string_sql(  $content_filecategory__id );

	if ( !$content_filecategory__id )
		$content_filecategory__id = 0;

	$tmp_content_filecategory__id = $content_filecategory__id;

	$counter=0;
	$KAT_ALL=array();
	while ($tmp_content_filecategory__id != 0) {
		$SQL_QUERY  = "SELECT * \n";
		$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
		$SQL_QUERY .= "WHERE content_filecategory__id='".$tmp_content_filecategory__id."' \n";

		try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_get_info()",$SQL_QUERY,$e); }
		
		if ($result->rowCount()<=0)
			error("Record not found by content_filecategory__id");

		$row = $result->fetch(PDO::FETCH_ASSOC);

		$KAT_ALL[$counter]["content_filecategory__id"]    = $row["content_filecategory__id"];
		$KAT_ALL[$counter]["content_filecategory__name"]  = $row["content_filecategory__name"];

		$tmp_content_filecategory__id = $row["content_filecategory__idparent"];
		$counter++;
	}

	$KAT_COUNT = count( $KAT_ALL ) - 1;

	$KAT_CURRENT[0]["content_filecategory__id"]   = $KAT_ALL[0]["content_filecategory__id"];
	$KAT_CURRENT[0]["content_filecategory__name"] = $KAT_ALL[0]["content_filecategory__name"];
	$KAT_TOP = $KAT_ALL[$KAT_COUNT];

	for ( $i=0; $i<$KAT_COUNT; $i++ )
		$KAT_REST[] = $KAT_ALL[$i];

	$KAT_REST_COUNT = count( $KAT_REST ) - 1;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_tree( $content_filecategory__id = 0, $tree, $content_filecategory__path = "" ) {

	$content_filecategory__id =  sm_secure_string_sql(  $content_filecategory__id );

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='".$content_filecategory__id."' \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_tree()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$tree[] = array(
				$row["content_filecategory__id"] => $content_filecategory__path . $row["content_filecategory__name"],
				"dane" => $row
			);
			$tree = content_filecategory_tree( $row["content_filecategory__id"], $tree, $content_filecategory__path.$row["content_filecategory__name"]." : " );
		}
	}
	return $tree;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_get( $content_filecategory__id ) {

	$content_filecategory__id =  sm_secure_string_sql(  $content_filecategory__id );

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__id='".$content_filecategory__id."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_add( $dane ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL, $ERROR;

	$dane = trimall( $dane );

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_user__id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_filecategory__id"] = uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_filecategory VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__comment"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_add()",$SQL_QUERY,$e); }

	return content_filecategory_edit( $dane );

}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_change( $dane ) {

	if($dane["content_filecategory__id"]) {
		$tmp_content_filecategory = content_filecategory_get($dane["content_filecategory__id"]);
		$dane["record_add"] = $tmp_content_filecategory["record_add"];
		$dane["id_admin_add"] = $tmp_content_filecategory["id_admin_add"];
		foreach($tmp_content_filecategory AS $k=>$v) {
			if (isset($dane[$k])){
				$tmp_content_filecategory[$k] = $dane[$k];
			}
		}
		$dane = $tmp_content_filecategory;
	}

	$dane["record_create_date"] = $dane["content_filecategory__id"] ? $tmp_content_filecategory["record_create_date"] : time();
	$dane["record_create_id"]   = $dane["content_filecategory__id"] ? $tmp_content_filecategory["record_create_id"]   : $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_filecategory VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__comment"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_change()",$SQL_QUERY,$e); }

	return content_filecategory_edit( $dane );
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_edit( $dane="" ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$dane = trimall($dane);

	content_filecategory_get_info( $dane["content_filecategory__id"] );
	if ($KAT_COUNT == 0) {
		$content_filecategory__idtop = 0;
		$content_filecategory__path[$KAT_COUNT]["content_filecategory__id"]      = $dane["content_filecategory__id"];
		$content_filecategory__path[$KAT_COUNT]["content_filecategory__name"]    = $dane["content_filecategory__name"];
	}
	else {
		$content_filecategory__idtop = $KAT_ALL[$KAT_COUNT]["content_filecategory__id"];
		$content_filecategory__path[0]["content_filecategory__id"]      = $dane["content_filecategory__id"];
		$content_filecategory__path[0]["content_filecategory__name"]    = $dane["content_filecategory__name"];
		for ( $i = 1; $i <= $KAT_COUNT; $i++) {
			$content_filecategory__path[$i]["content_filecategory__id"]      = $KAT_ALL[$i]["content_filecategory__id"];
			$content_filecategory__path[$i]["content_filecategory__name"]    = $KAT_ALL[$i]["content_filecategory__name"];
		}
	}

	$parent = content_filecategory_get( $dane["content_filecategory__id"] );

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_filecategory VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $parent["content_filecategory__idparent"]).", \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__comment"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( json_encode($content_filecategory__path))."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_filecategory__idtop)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_filecategory__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_edit()",$SQL_QUERY,$e); }

	return $dane["content_filecategory__id"];
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_delete( $content_filecategory__id ) {
	global $CONTENT_FILECATEGORY;
	global $ERROR;

	if (!$content_filecategory__id)
		return 0;

	$SQL_QUERY  = "SELECT count(content_filecategory__id) as ilosc \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='". sm_secure_string_sql( $content_filecategory__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_delete:select()",$SQL_QUERY,$e); }

	$row = $result->fetch(PDO::FETCH_ASSOC);
	if( $row["ilosc"]>0 )
		$ERROR[] = "Nie mozesz usunac elementu, poniewaz zawiera podelementy";

	if( is_array( $ERROR ) )
		return false;

	if ($deleted = content_filecategory_dane( $content_filecategory__id ) ) {
		core_deleted_add( $content_filecategory__id, "content_filecategory", $deleted );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__id='". sm_secure_string_sql( $content_filecategory__id)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_delete:delete()",$SQL_QUERY,$e); }

	return $CONTENT_FILECATEGORY[$content_filecategory__id]["idparent"];
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory AS cp, ".DB_TABLEPREFIX."_content_template AS ct \n";
	$SQL_QUERY .= "ORDER BY cp.content_filecategory__idtop \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_show_path( $content_filecategory__id=0 ) {

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent = '". sm_secure_string_sql( $content_filecategory__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__idtop, content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_show_path()",$SQL_QUERY,$e); }

	if ( $result->rowCount() > 0 ) {
		while ( $row = $result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$content_filecategory["content_filecategory__id"]]=$row["content_filecategory__name"];
	}
	return $retarr;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_show_parent( $content_filecategory__id ) {
	if ( $content_filecategory__id)
		$id_parent = $content_filecategory__id;
	elseif ( empty($id_parent) )
		$id_parent = 0;

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent = '". sm_secure_string_sql( $id_parent)."' \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_show_parent()",$SQL_QUERY,$e); }

	if( $result->rowCount() > 0 ) {
		while( $row=$result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$row["content_filecategory__id"]] = stripslashesall($row);
	}
	return $retarr;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_show_all_parent( $id_parent ) {
	$possible_content_items = "";
	$SQL_QUERY  = "SELECT DISTINCT content_filecategory__id \n ";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_show_all_parent:select1()",$SQL_QUERY,$e); }

	$counter = 0;
	while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_filecategory__id"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT DISTINCT content_filecategory__idparent \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_show_all_parent:select2()",$SQL_QUERY,$e); }

	while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_filecategory__idparent"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='". sm_secure_string_sql( $id_parent)."' \n";
	$SQL_QUERY .= "  AND content_filecategory__id in (".$possible_content_items.") \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_show_all_parent:select3()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result : 0;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_unserialize($content_filecategory__id) {

	$content_filecategory__id = ( $content_filecategory__id ? $content_filecategory__id : 0 );

	if ( $content_filecategory__id ) {
		$content_filecategory = content_filecategory_get( $content_filecategory__id );
		$temp_ser_path = $content_filecategory["content_filecategory__path"];
		if( isset( $temp_ser_path ) ) {
			$temp_path = json_decode($temp_ser_path, $assoc=true);
			if( is_array( $temp_path ) ) {
				$temp_full_path = "";
				$cnt = sizeof( $temp_path );
				$temp_id = $temp_path[0]["content_filecategory__id"];
				for( $i = $cnt-1; $i >= 0; $i-- ) {
					$retarr[$temp_path[$i]["content_filecategory__id"]] = $temp_path[$i]["content_filecategory__name"];
				}
			}
		}
	}

	return $retarr;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_by_parent( $content_filecategory__idparent ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='". sm_secure_string_sql( $content_filecategory__idparent)."' \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__name, content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_by_parent()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result : 0;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_count_by_parent( $content_filecategory__idparent ) {

	$SQL_QUERY  = "SELECT COUNT(*) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='". sm_secure_string_sql( $content_filecategory__idparent)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_count_by_parent()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_get_tree( $content_filecategory__id = 0 ) {
	global $tree;
	content_filecategory_get_tree_recurency($content_filecategory__id);
	return $tree;
}

/**
 * @category	content_filecategory
 * @package		sql
 * @version		5.0.0
*/
function content_filecategory_get_tree_recurency( $content_filecategory__id = 0 ) {
	global $tree;

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_filecategory \n";
	$SQL_QUERY .= "WHERE content_filecategory__idparent='". sm_secure_string_sql( $content_filecategory__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_filecategory__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_filecategory_get_tree_recurency()",$SQL_QUERY,$e); }

	if ( $result->rowCount() > 0 ) {
		while( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
			$tree[] = $row;
			content_filecategory_get_tree_recurency( $row["content_filecategory__id"]);
		}
	}
}

?>