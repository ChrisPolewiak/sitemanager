<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


function smarty_function_sitemanager_menu_tree_recurency( $element, $idname, $level, $id, $size, $newpath, $_get="" ) {
    
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
			$return = smarty_function_sitemanager_menu_tree_recurency( $item, $idname, $level, $id, $size, $newpath, $_get );
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

function smarty_function_sitemanager_menu_clear_recurency( $menulist ) {


	if( is_array($menulist["items"]) ) {
		foreach($menulist["items"] AS $item) {
			if($item["path"]) {             
				return $menulist;
			}
			else {
				return smarty_function_sitemanager_menu_clear_recurency($item);
			}
		}
	}

}

/**
 * Smarty Sitemanager Menu List
 *
 * Type:     function<br>
 * Name:     sitemanager_menu_tree<br>
 * Purpose:  Get array of submenu
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_image_resize.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sitemanager_menu_tree($params, &$smarty) {

	$smarty->clear_assign("menu_list");
	$smarty->clear_assign("menu_list_title");

	$tree       = strtoupper($params["tree"]);
	$level      = $params["level"];
	$id         = $params["id"];
	$size       = $params["size"];

	if(is_object($GLOBALS["XML_".$tree])) {
		$xmlsrc = $GLOBALS["XML_".$tree];
		$idname = strtolower("id_".$tree);

		$xpath = "//item[@$idname='$id']/self::*";

		$current = $xmlsrc->xpath($xpath);

		$path = unserialize( stripslashes( (string) $current[0]->path ) );

		$path = array_reverse($path);
		$counter=0;
		foreach($path AS $k=>$v) {
			$counter++;
			if($counter>=$level) {
				$newpath[$v[$idname]] = $v[ strtolower($tree)."_name"];
				$selectpath[] = $v[$idname];
			}
		}

		$tmp = smarty_function_sitemanager_menu_tree_recurency($xmlsrc, $idname, $level, $id, $size, $newpath);
		$tmp = smarty_function_sitemanager_menu_clear_recurency( $tmp );
		$menu_list = $tmp["items"];
		$menu_list_title = $tmp;
		unset($menu_list_title["items"]);


		$smarty->assign("menu_list", $menu_list);
		$smarty->assign("menu_list_title", $menu_list_title);
                $smarty->assign("menu_list_path", $newpath);
                $smarty->assign("menu_select_path", $selectpath);
#print_r($newpath);
#print_r($selectpath);
#print_r($menu_list);

	}
}

?>
