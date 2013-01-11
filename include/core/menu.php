<?
/**
 * sitemanager_menu
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	sitemanager_menu
 */

/**
 * @category	sitemanager_menu
 * @package		core
 * @version		5.0.0
*/
function sm_menu_tree_recurency( $element, $idname, $level, $id, $size, $newpath, $_get="" ) {
    
	$_level = (int) xml_findAttribute( $element, "size" );
	$_id = (int) xml_findAttribute( $element, $idname );
	$_title = (string) xml_findAttribute( $element, "title" );
	$_name = (string) xml_findAttribute( $element, "name" );

#echo " ($_name) \t $_level>=$level && $_level<=$size+$level && $newpath[$_id] ) || ( $_level>=$level && $_level<=$size+$level && $_get \n";

	if( ( $_level>=$level && $_level<=$size+$level && $newpath[$_id] ) || ( $_level>=$level && $_level<=$size+$level && $_get ) ) {
#	if( ( $_level>=$level && $_level<=$size+$level ) || ( $_level>=$level && $_level<=$size+$level && $_get ) ) {
#echo " _get\n";
		$_get=true;
	}
	elseif( ! $_level==0) {
		$_get=false;
	}
	else {
		$_get=true;
	}

	if($_get) {
		if( (int) xml_findAttribute($element, "param_menu_disabled") == 1 ) {
			$_get=false;
		}
		else {
			if($element->path)
				$menulist["path"] = (string) $element->path;

			foreach($element->attributes() as $k => $v) {
				$menulist[$k] = (string) $v;
				if($k==$idname && $v==$id) {
					$menulist["selected"] = 1;
				}
			}
		}
	}

	if(is_object($element->item)) {
		foreach($element->item AS $item) {
			$return = sm_menu_tree_recurency( $item, $idname, $level, $id, $size, $newpath, $_get );
			if($return){
				$menulist["items"][ $return[$idname] ] = $return;
			}
		}
	}
	else {
		$_get = false;
	}

	return $menulist;
}

/**
 * @category	sitemanager_menu
 * @package		core
 * @version		5.0.0
*/
function sm_menu_clear_recurency( $menulist ) {


	if( is_array($menulist["items"]) ) {
		foreach($menulist["items"] AS $item) {
			if($item["path"]) {             
				return $menulist;
			}
			else {
				return sm_menu_clear_recurency($item);
			}
		}
	}

}

/**
 * Sitemanager Menu List
 * 
 * @category	sitemanager_menu
 * @package		core
 * @version		5.0.0
 * @link http://cms.ocenter.pl/manual/pl/core.sitemanager_menu.sm_menu_tree.php
 * @param tree
 * @param level
 * @param id
 * @param size
 * @return string
*/
function sm_menu_tree($tree, $level, $id, $size ) {

	if(is_object($GLOBALS["XML_".$tree])) {
		$xmlsrc = $GLOBALS["XML_".$tree];
		$idname = strtolower($tree."__id");


		$xpath = "//item[@id='$id']/self::*";

		$current = $xmlsrc->xpath($xpath);

		$path = unserialize( stripslashes( (string) $current[0]->_path ) );

		$path = array_reverse($path);
		$counter=0;
		foreach($path AS $k=>$v) {
			$counter++;
			if($counter>=$level) {
				$newpath[$v[$idname]] = $v[ strtolower($tree)."__name"];
				$selectpath[] = $v[$idname];
			}
		}

		$tmp = sm_menu_tree_recurency($xmlsrc, $idname, $level, $id, $size, $newpath);
		$tmp = sm_menu_clear_recurency( $tmp );
		$menu_list = $tmp["items"];
		$menu_list_title = $tmp;
		unset($menu_list_title["items"]);

		return array(
			"menu_list" => $menu_list,
			"menu_list_title" => $menu_list_title,
			"newpath" => $newpath,
			"selectpath" => $selectpath
		);
	}
}

?>