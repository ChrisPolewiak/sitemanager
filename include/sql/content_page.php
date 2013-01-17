<?
/**
 * content_page
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		sql
 * @category	content_page
 */

if (is_file($ROOT_DIR."/cache/content_page.php")) {
	include $ROOT_DIR."/cache/content_page.php";
}
if (is_file($ROOT_DIR."/cache/content_page.xml")) {
	$XML_CONTENT_PAGE = simplexml_load_file($ROOT_DIR."/cache/content_page.xml");
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_info( $content_page__id ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL;

	$content_page__id =  sm_secure_string_sql( $content_page__id);

	if ( !$content_page__id )
		$content_page__id = 0;

	$tmp_content_page__id = $content_page__id;

	$counter=0;
	$KAT_ALL=array();
	while ($tmp_content_page__id) {
		$SQL_QUERY  = "SELECT * \n";
		$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
		$SQL_QUERY .= "WHERE content_page__id='".$tmp_content_page__id."' \n";

		try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_get_info()",$SQL_QUERY,$e); }

		if ($result->rowCount() <= 0)
			error("Record not found by content_page__id");

		$row = $result->fetch(PDO::FETCH_ASSOC);

		$KAT_ALL[$counter]["content_page__id"]    = $row["content_page__id"];
		$KAT_ALL[$counter]["content_page__name"]  = $row["content_page__name"];

		$tmp_content_page__id = $row["content_page__idparent"];
		$counter++;
	}

	$KAT_COUNT = count( $KAT_ALL ) - 1;

	$KAT_CURRENT[0]["content_page__id"]   = $KAT_ALL[0]["content_page__id"];
	$KAT_CURRENT[0]["content_page__name"] = $KAT_ALL[0]["content_page__name"];
	$KAT_TOP = $KAT_ALL[$KAT_COUNT];

	for ( $i=0; $i<$KAT_COUNT; $i++ )
		$KAT_REST[] = $KAT_ALL[$i];

	$KAT_REST_COUNT = count( $KAT_REST ) - 1;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_tree( $content_page__id = 0, $tree, $content_page__path = '' ) {

	$content_page__id =  sm_secure_string_sql( $content_page__id);

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent='".$content_page__id."' \n";
	$SQL_QUERY .= "ORDER BY content_page__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_tree()",$SQL_QUERY,$e); }

	if ( $result->rowCount()>0 ) {
		while( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
			$tree[] = array(
				$row["content_page__id"] => $content_page__path.$row["content_page__name"],
				"dane" => $row
			);
			$tree = content_page_tree( $row["content_page__id"], $tree, $content_page__path.$row["content_page__name"]." : " );
		}
	}
	return $tree;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get( $content_page__id ) {

	$content_page__id =  sm_secure_string_sql( $content_page__id);

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__id='".$content_page__id."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_get()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result->fetch(PDO::FETCH_ASSOC) : 0);
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_add( $dane ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL, $ERROR;

	$dane = trimall( $dane );

	if( !$dane["content_page__name"] ) {
		$ERROR[] = "Brak nazwy ";
	}

	if( is_array( $ERROR ) ) return false;

	$dane["record_create_date"] = time();
	$dane["record_create_id"] = $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_page__requiredaccess"] = $dane["content_page__requiredaccess"] ? $dane["content_page__requiredaccess"] : 0;
	$dane["content_page__menu_visible"] = $dane["content_page__menu_visible"] ? 1 : 0;
	$dane["content_page__sitemap_visible"] = $dane["content_page__sitemap_visible"] ? 1 : 0;
	$dane["content_page__enabled"] = $dane["content_page__enabled"] ? 1 : 0;

	$dane["content_page__id"] = uuid();

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_page VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__info"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__title"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__description"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__keywords"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__lang"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__params"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__hostallow"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__requiredaccess"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__menu_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__sitemap_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__enabled"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_page"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_add()",$SQL_QUERY,$e); }

	return content_page_edit( $dane );

}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_change( $dane ) {

	if($dane["content_page__id"]) {
		$tmp_content_page = content_page_get($dane["content_page__id"]);
		$dane["content_page__idtop"] = $tmp_content_page["content_page__idtop"];
		$dane["content_page__path"] = $tmp_content_page["content_page__path"];
	}

	$dane["content_page__order"] = isset($dane["content_page__order"]) ? $dane["content_page__order"] : $tmp_content_page["content_page__order"];

	$dane["record_create_date"] = $dane["content_page__id"] ? $tmp_content_page["record_create_date"] : time();
	$dane["record_create_id"]   = $dane["content_page__id"] ? $tmp_content_page["record_create_id"]   : $_SESSION["content_user"]["content_user__id"];
	$dane["record_modify_date"] = time();
	$dane["record_modify_id"] = $_SESSION["content_user"]["content_user__id"];

	$dane["content_page__requiredaccess"] = $dane["content_page__requiredaccess"] ? $dane["content_page__requiredaccess"] : 0;
	$dane["content_page__menu_visible"] = $dane["content_page__menu_visible"] ? 1 : 0;
	$dane["content_page__sitemap_visible"] = $dane["content_page__sitemap_visible"] ? 1 : 0;
	$dane["content_page__enabled"] = $dane["content_page__enabled"] ? 1 : 0;

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_page VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__info"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__title"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__idtop"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__description"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__keywords"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__lang"])."', \n";
	$SQL_QUERY .= "0, \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__params"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__hostallow"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__requiredaccess"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__menu_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__sitemap_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__enabled"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_page"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_change()",$SQL_QUERY,$e); }

	return content_page_edit( $dane );
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_reorder( $content_page__id, $content_page__idparent, $content_page__order) {

	$content_page__idparent = $content_page__idparent ? $content_page__idparent : 0;
	$content_page__idtop = 0;

	$content_page = content_page_get( $content_page__id );
	$content_page__path[] = array(
		"content_page__id" => $content_page__id,
		"content_page__name" => $content_page["content_page__name"]
	);

	$content_page = content_page_get( $content_page__id );
	if( $content_page__idparent ) {
		$content_page_parent = content_page_get( $content_page__idparent );
		$_path = json_decode($content_page_parent["content_page__path"], $assoc=true);

		foreach($_path AS $k=>$v) {
			$content_page__path[] = array(
				"content_page__id" => $v["content_page__id"],
				"content_page__name" => $v["content_page__name"]
			);

			$content_page__idtop = $v["content_page__id"];

		}
	}

	$SQL_QUERY  = "UPDATE ".DB_TABLEPREFIX."_content_page SET \n";
	$SQL_QUERY .= " content_page__idparent='" . sm_secure_string_sql( $content_page__idparent ). "', \n";
	$SQL_QUERY .= " content_page__idtop='" . sm_secure_string_sql( $content_page__idtop ). "', \n";
	$SQL_QUERY .= " content_page__order='" . sm_secure_string_sql( $content_page__order ). "', \n";
	$SQL_QUERY .= " content_page__path='" . sm_secure_string_sql(json_encode($content_page__path)). "' \n";
	$SQL_QUERY .= "WHERE content_page__id = '". sm_secure_string_sql( $content_page__id ) ."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_reorder()",$SQL_QUERY,$e); }

	return 1;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_edit( $dane="" ) {
	global $KAT_COUNT, $KAT_CURRENT, $KAT_TOP, $KAT_REST_COUNT, $KAT_REST, $KAT_ALL, $ERROR;

	$dane = trimall($dane);

	content_page_get_info( $dane["content_page__id"] );
	if ($KAT_COUNT == 0) {
		$content_page__idtop = 0;
		$content_page__path[$KAT_COUNT]["content_page__id"]		= $dane["content_page__id"];
		$content_page__path[$KAT_COUNT]["content_page__name"]	= $dane["content_page__name"];
	}
	else {
		$content_page__idtop = $KAT_ALL[$KAT_COUNT]["content_page__id"];
		$content_page__path[0]["content_page__id"]				= $dane["content_page__id"];
		$content_page__path[0]["content_page__name"]			= $dane["content_page__name"];
		for ( $i = 1; $i <= $KAT_COUNT; $i++) {
			$content_page__path[$i]["content_page__id"]			= $KAT_ALL[$i]["content_page__id"];
			$content_page__path[$i]["content_page__name"]		= $KAT_ALL[$i]["content_page__name"];
		}
	}

	$parent = content_page_get( $dane["content_page__id"] );

	$SQL_QUERY  = "REPLACE INTO ".DB_TABLEPREFIX."_content_page VALUES ( \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $parent["content_page__idparent"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__name"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__info"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__title"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $content_page__idtop)."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__order"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__description"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__keywords"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__lang"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( json_encode($content_page__path))."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_template__id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__params"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__hostallow"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__requiredaccess"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__menu_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__sitemap_visible"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__enabled"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_url"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["content_page__redirect_page"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_create_id"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_date"])."', \n";
	$SQL_QUERY .= "'". sm_secure_string_sql( $dane["record_modify_id"])."' \n";
	$SQL_QUERY .= ")\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_edit()",$SQL_QUERY,$e); }

	return $dane["content_page__id"];
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_delete( $content_page__id ) {
	global $CONTENT_PAGE;
	global $ERROR;

	if (!$content_page__id)
		return 0;

	$SQL_QUERY  = "SELECT count(content_page__id) as ilosc\n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page\n";
	$SQL_QUERY .= "WHERE content_page__idparent='". sm_secure_string_sql( $content_page__id)."'\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_delete:select()",$SQL_QUERY,$e); }

	$row = $result->fetch(PDO::FETCH_ASSOC);
	if( $row["ilosc"] > 0 )
		$ERROR[] = "Nie mozesz usunac elementu, poniewaz zawiera podelementy";

	if( is_array( $ERROR ) )
		return false;

	if ($deleted = content_page_get( $content_page__id ) ) {
		core_deleted_add( $content_page__id, "content_page", $deleted );
	}

	$SQL_QUERY  = "DELETE FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__id='". sm_secure_string_sql( $content_page__id)."'\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_delete:delete()",$SQL_QUERY,$e); }

	return $CONTENT_PAGE[$content_page__id]["idparent"];
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_fetch_all() {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page AS cp, ".DB_TABLEPREFIX."_content_template AS ct \n";
	$SQL_QUERY .= "WHERE ct.content_template__id=cp.content_template__id \n";
	$SQL_QUERY .= "ORDER BY content_page__idtop, content_page__order\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_fetch_all()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_fetch_by_lang($lang) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page AS cp, ".DB_TABLEPREFIX."_content_template AS ct \n";
	$SQL_QUERY .= "WHERE ct.content_template__id=cp.content_template__id \n";
	$SQL_QUERY .= "  AND cp.content_page__lang='". sm_secure_string_sql( $lang)."' \n";
	$SQL_QUERY .= "ORDER BY content_page__idtop, content_page__order\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_fetch_by_lang()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_show_path( $content_page__id=0 ) {

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent = '". sm_secure_string_sql( $content_page__id)."' \n";
	$SQL_QUERY .= "ORDER BY content_page__idtop, content_page__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_show_path()",$SQL_QUERY,$e); }

	if ( $result->rowCount()>0 ) {
		while ( $row = $result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$row["content_page__id"]]=$row["content_page__name"];
	}
	return $retarr;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_show_parent( $content_page__id ) {

	if ( $content_page__id)
		$id_parent = $content_page__id;
	elseif ( empty($id_parent) )
		$id_parent = 0;

	$SQL_QUERY  = "SELECT * FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent = '". sm_secure_string_sql( $id_parent)."' \n";
	$SQL_QUERY .= "ORDER BY content_page__order, content_page__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_show_parent()",$SQL_QUERY,$e); }

	if( $result->rowCount()>0 ) {
		while ( $row = $result->fetch(PDO::FETCH_ASSOC) )
			$retarr[$row["content_page__id"]] = stripslashesall($row);
	}
	return $retarr;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_show_all_parent( $id_parent ) {

	$possible_content_items = "";
	$SQL_QUERY  = "SELECT DISTINCT content_page__id \n ";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_show_all_parent:select1()",$SQL_QUERY,$e); }

	$counter = 0;
	while ( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_page__id"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT DISTINCT content_page__idparent \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page\n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_show_all_parent:select2()",$SQL_QUERY,$e); }

	while ( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
		if ($counter > 0)
			$possible_content_items .= ",";
		$possible_content_items .= $row["content_page__idparent"];
		$counter++;
	}

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent='".$id_parent."' \n";
	$SQL_QUERY .= "  AND content_page__id in (".$possible_content_items.") \n";
	$SQL_QUERY .= "ORDER BY content_page__name \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_show_all_parent:select3()",$SQL_QUERY,$e); }

	return ($result->rowCount()>0 ? $result : 0);
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_unserialize($content_page__id) {

	$content_page__id = ( $content_page__id ? $content_page__id : 0 );

	if ( $content_page__id ) {
		$content_page = content_page_get( $content_page__id );
		$temp_ser_path = $content_page["content_page__path"];
		if( isset( $temp_ser_path ) ) {
			$temp_path = json_decode($temp_ser_path, $assoc=true);
			if( is_array( $temp_path ) ) {
				$temp_full_path = "";
				$cnt = sizeof( $temp_path );
				$temp_id = $temp_path[0]["content_page__id"];
				for( $i = $cnt-1; $i >= 0; $i-- ) {
					$retarr[$temp_path[$i]["content_page__id"]] = $temp_path[$i]["content_page__name"];
				}
			}
		}
	}

	return $retarr;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_by_parent( $content_page__idparent ) {

	$SQL_QUERY  = "SELECT * \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent='". sm_secure_string_sql( $content_page__idparent)."' \n";
	$SQL_QUERY .= "ORDER BY content_page__order, content_page__name ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_by_parent()",$SQL_QUERY,$e); }

	return $result->rowCount()>0 ? $result : 0;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_count_by_parent( $content_page__idparent ) {

	$SQL_QUERY  = "SELECT COUNT(*) AS ile \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__idparent='". sm_secure_string_sql( $content_page__idparent)."' ";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_count_by_parent()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["ile"];
	}
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_tree( $content_page__id = 0 ) {
	global $tree;
	content_page_get_tree_recurency($content_page__id);
	return $tree;
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_tree_recurency( $content_page__id = 0 ) {
	global $tree;

	$SQL_QUERY  = "SELECT *\n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page\n";
	$SQL_QUERY .= "WHERE content_page__idparent='". sm_secure_string_sql( $content_page__id)."'\n";
	$SQL_QUERY .= "ORDER BY content_page__order \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("core_session_fetch_all()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		while( $row=$result->fetch(PDO::FETCH_ASSOC) ) {
			$tree[] = $row;
			content_page_get_tree_recurency( $row["content_page__id"]);
		}
	}
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_last_order( $content_page__idparent ) {

	$SQL_QUERY  = "SELECT content_page__order \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page\n";
	$SQL_QUERY .= "WHERE content_page__idparent='". sm_secure_string_sql( $content_page__idparent)."'\n";
	$SQL_QUERY .= "ORDER BY content_page__order DESC \n";
	$SQL_QUERY .= "LIMIT 1 \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_get_last_order()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_page__order"];
	}
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_by_name( $content_page__name ) {

	$SQL_QUERY  = "SELECT content_page__id \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__name='". sm_secure_string_sql( $content_page__name)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_get_by_name()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_page__id"];
	}
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_get_by_url( $content_page__url ) {

	$SQL_QUERY  = "SELECT content_page__id \n";
	$SQL_QUERY .= "FROM ".DB_TABLEPREFIX."_content_page \n";
	$SQL_QUERY .= "WHERE content_page__url='". sm_secure_string_sql( $content_page__url)."' \n";

	try { $result = $GLOBALS["SM_PDO"]->query($SQL_QUERY); } catch(PDOException $e) { sqlerr("content_page_get_by_url()",$SQL_QUERY,$e); }

	if ($result->rowCount()>0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row["content_page__id"];
	}
}

/**
 * @category	content_page
 * @package		sql
 * @version		5.0.0
*/
function content_page_refresh() {
	global $CONTENT_PAGE;

	if($result = content_page_fetch_all() ) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			content_page_reorder( $row["content_page__id"], $row["content_page__idparent"], $row["content_page__order"]);
		}
	}
}
	
?>