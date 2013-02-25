<?
/**
 * content_cache
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		1.0
 * @package		sql
 * @category	content_category
 */

if (is_file($ROOT_DIR."/include/genfiles/content_category.inc")) {
	include $ROOT_DIR."/include/genfiles/content_category.inc";
}
if (is_file($ROOT_DIR."/include/genfiles/content_category.xml")) {
	$XML_CONTENT_CATEGORY = simplexml_load_file($ROOT_DIR."/include/genfiles/content_category.xml");
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_get_info( $content_category__id ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$content_category__id =  sm_secure_string_sql(  $content_category__id );

	if ( !$content_category__id )
		$content_category__id = 0;

	$tmp_content_category__id = $content_category__id;

	$counter=0;
	$KAT_ALL=array();
	while ($tmp_content_category__id) {

		$SQL_QUERY  = "SELECT * \n";
		$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
		$SQL_QUERY .= "WHERE content_category__id='".$tmp_content_category__id."' \n";

		try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_get_info()",$SQL_QUERY,$e); }

		if ($result->rowCount() <= 0 )
			error("Record not found by content_category__id");

		$row = $result->fetch(PDO::FETCH_ASSOC);

		$KAT_ALL[$counter]["content_category__id"]    = $row["content_category__id"];
		$KAT_ALL[$counter]["content_category__name"]  = $row["content_category__name"];

		$tmp_content_category__id = $row["content_category__idparent"];
		$counter++;
	}

	$KAT_COUNT = count( $KAT_ALL ) - 1;

	$KAT_CURRENT[0]["content_category__id"]   = $KAT_ALL[0]["content_category__id"];
	$KAT_CURRENT[0]["content_category__name"] = $KAT_ALL[0]["content_category__name"];
	$KAT_TOP = $KAT_ALL[$KAT_COUNT];

	for ( $i=0; $i<$KAT_COUNT; $i++ )
		$KAT_REST[] = $KAT_ALL[$i];

	$KAT_REST_COUNT = count( $KAT_REST ) - 1;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_tree( $content_category__id = 0, $tree, $content_category__path = '' ) {

	$content_category__id =  sm_secure_string_sql(  $content_category__id );

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='".$content_category__id."' \n";
	$SQL_QUERY .= "ORDER BY content_category__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_tree()",$SQL_QUERY,$e); }

	if ( $result->rowCount() > 0 ) {
		while( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
			$tree[] = array(
				$row["content_category__id"] => $content_category__path . $row["content_category__name"],
				"dane" => $row);
			$tree = content_category_tree( $row["content_category__id"], $tree, $content_category__path.$row["content_category__name"]." : " );
		}
	}
	return $tree;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_get( $content_category__id ) {

	$content_category__id =  sm_secure_string_sql(  $content_category__id );

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__id='".$content_category__id."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_get()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_add( $dane ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$dane = trimall( $dane );

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_category__private"] = $dane["content_category__private"] ? 1 : 0;
	$dane["content_category__order"]   = $dane["content_category__order"] ? $dane["content_category__order"] : 0;

	$dane["content_category__id"] = uuid();
	core_changed_add( $dane["content_category__id"], "content_category", $tmp_content_category="", "add" );

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_category VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__comment"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_add()",$SQL_QUERY,$e); }
	
	return content_category_edit( $dane );
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_change( $dane ) {

	if($dane["content_category__id"]) {
		$tmp_content_category = content_category_get($dane["content_category__id"]);
		foreach($tmp_content_category AS $k=>$v) {
			if (isset($dane[$k])){
				$tmp_content_category[$k] = $dane[$k];
			}
		}
		$dane = $tmp_content_category;
	}

	$dane["record_create_date"] = $dane["content_category__id"] ? $tmp_content_category["record_create_date"] : time();
	$dane["record_create_id"]   = $dane["content_category__id"] ? $tmp_content_category["record_create_id"]   : $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"]   = $_SESSION["content_user"]["content_user__id"];
	core_changed_add( $dane["content_category__id"], "content_category", $tmp_content_category, "edit" );

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_category VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__comment"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_change()",$SQL_QUERY,$e); }

	return content_category_edit( $dane );
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.1
*/
function content_category_edit( $dane="" ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$dane = trimall($dane);
	$dane = stripslashesall($dane);

	content_category_get_info( $dane["content_category__id"] );
	if ($KAT_COUNT == 0) {
		$content_category__idtop = 0;
		$content_category__path[$KAT_COUNT]["content_category__id"]      = $dane["content_category__id"];
		$content_category__path[$KAT_COUNT]["content_category__name"]    = $dane["content_category__name"];
	}
	else {
		$content_category__idtop = $KAT_ALL[$KAT_COUNT]["content_category__id"];
		$content_category__path[0]["content_category__id"]      = $dane["content_category__id"];
		$content_category__path[0]["content_category__name"]    = $dane["content_category__name"];
		for ( $i = 1; $i <= $KAT_COUNT; $i++) {
			$content_category__path[$i]["content_category__id"]      = $KAT_ALL[$i]["content_category__id"];
			$content_category__path[$i]["content_category__name"]    = $KAT_ALL[$i]["content_category__name"];
		}
	}

	$parent = content_category_get( $dane["content_category__id"] );

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_category VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $parent["content_category__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__comment"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( json_encode($content_category__path))."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_category__idtop)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_user__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_category__private"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ") \n";

	try { $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_edit()",$SQL_QUERY,$e); }

	return $dane["content_category__id"];
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_delete( $content_category__id ) {
	global $CONTENT_CATEGORY;
	global $ERROR;

	if (!$content_category__id)
		return 0;

	$content_category__id =  sm_secure_string_sql(  $content_category__id );

	$SQL_QUERY  = "SELECT count(content_category__id) as ilosc \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='".$content_category__id."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_delete:select()",$SQL_QUERY,$e); }

	$row = $result->fetch(PDO::FETCH_ASSOC);
	if( $row["ilosc"] > 0 )
		$ERROR[] = "Nie mozesz usunac elementu, poniewaz zawiera podelementy";

	if( is_array( $ERROR ) )
		return false;

	if ($deleted = content_category_dane( $content_category__id ) ) {
		core_changed_add( $content_category__id, "content_category", $deleted, "del" );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__id='".$content_category__id."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_delete:delete()",$SQL_QUERY,$e); }

	return $CONTENT_CATEGORY[$content_category__id]["idparent"];
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category AS cp, ".DB_TABLEPREFIX."_content_template AS ct \n";
	$SQL_QUERY .= "ORDER BY cp.content_category__idtop, cp.content_category__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_show_path( $content_category__id=0 ) {

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent = '". sm_secure_string_sql( $content_category__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_category__idtop, content_category__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_show_path()",$SQL_QUERY,$e); }

	if ( $result->rowCount() > 0 ) {
		while ( $content_category = $result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$content_category["content_category__id"]]=$content_category["content_category__name"];
	}
	return $retarr;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_show_parent( $content_category__id ) {

	if ( $content_category__id )
		$id_parent = $content_category__id;
	elseif ( empty($id_parent) )
		$id_parent = 0;

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent = '". sm_secure_string_sql( $id_parent)."' \n";
	$SQL_QUERY .= "ORDER BY content_category__order, content_category__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_show_parent()",$SQL_QUERY,$e); }

	if( $result->rowCount() > 0 ) {
		while( $row=$result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$row["content_category__id"]] = stripslashesall($row);
	}
	return $retarr;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_show_all_parent( $id_parent ) {

	$possible_content_items = "";
	$SQL_QUERY  = "SELECT DISTINCT content_category__id \n ";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_show_all_parent:select1()",$SQL_QUERY,$e); }

	$counter = 0;
	while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_category__id"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT DISTINCT content_category__idparent \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_show_all_parent:select2()",$SQL_QUERY,$e); }

	while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_category__idparent"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='". sm_secure_string_sql( $id_parent)."' \n";
	$SQL_QUERY .= "  AND content_category__id IN (".$possible_content_items.") \n";
	$SQL_QUERY .= "ORDER BY content_category__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_show_all_parent:select3()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result : 0;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.1
*/
function content_category_unserialize($content_category__id) {
	$content_category__id = ( $content_category__id ? $content_category__id : 0 );

	if ( $content_category__id ) {
		$content_category = content_category_get( $content_category__id );
		$temp_ser_path = $content_category["content_category__path"];
		if( isset( $temp_ser_path ) ) {
			$temp_path = json_decode($temp_ser_path, $assoc=true);
			if( is_array( $temp_path ) ) {
				$temp_full_path = "";
				$cnt = sizeof( $temp_path );
				$temp_id = $temp_path[0]["content_category__id"];
				for( $i = $cnt-1; $i >= 0; $i-- ) {
					$retarr[$temp_path[$i]["content_category__id"]] = $temp_path[$i]["content_category__name"];
				}
			}
		}
	}

	return $retarr;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_by_parent( $content_category__idparent ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='". sm_secure_string_sql( $content_category__idparent)."' \n";
	$SQL_QUERY .= "ORDER BY content_category__order, content_category__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_by_parent()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result : 0;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_count_by_parent( $content_category__idparent ) {
	$SQL_QUERY  = "SELECT COUNT(*) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='". sm_secure_string_sql( $content_category__idparent)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_count_by_parent()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_get_tree( $content_category__id = 0 ) {
	global $tree;
	content_category_get_tree_recurency($content_category__id);
	return $tree;
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_get_tree_recurency( $content_category__id = 0 ) {
	global $tree;

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category \n";
	$SQL_QUERY .= "WHERE content_category__idparent='". sm_secure_string_sql( $content_category__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_category__order, content_category__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_get_tree_recurency()",$SQL_QUERY,$e); }

	if ( $result->rowCount() > 0 ) {
		while( $row = mysql_fetch_assoc( $result ) ) {
			$tree[] = $row;
			content_category_get_tree_recurency( $row["content_category__id"]);
		}
	}
}

/**
 * @category	content_category
 * @package		sql
 * @version		5.0.0
*/
function content_category_get_last_order( $content_category__idparent ) {

	$SQL_QUERY  = "SELECT content_category__order \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_category\n";
	$SQL_QUERY .= "WHERE content_category__idparent='". sm_secure_string_sql( $content_category__idparent)."'\n";
	$SQL_QUERY .= "ORDER BY content_category__order DESC \n";
	$SQL_QUERY .= "LIMIT 1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_category_get_last_order()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_category__order"];
	}
}

?>