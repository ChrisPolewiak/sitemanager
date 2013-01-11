<?

/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager sitemap
 *
 * Type:     function<br>
 * Name:     sitemanager_sitemap<br>
 * Purpose:  bring joy and happiness to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
 
function smarty_function_sitemanager_sitemap($params, &$smarty) {

	$xpath = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=2]/child::*");
	foreach($xpath AS $v) {
		if($v && $v->item) {
			if ( (int) xml_findAttribute( $v, "param_menu_disabled") )
			continue;	
			$_id = (int) xml_findAttribute( $v, "id_contentpage");
			$_title = (string) xml_findAttribute( $v, "name");
			$_url = (string) xml_findAttribute( $v, "url");

			$smarty->assign("id1", $_id);
			$smarty->assign("title1", $_title);
			$smarty->assign("url1", $_url);
		
			$xpath2 = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=$_id]/child::*");
			foreach($xpath2 AS $v2) {
				if($v2) {	
					if ( (int) xml_findAttribute( $v2, "param_menu_disabled") )
					continue;	
					$_id = (int) xml_findAttribute( $v2, "id_contentpage");
					$_title = (string) xml_findAttribute( $v2, "title");
					$_url = (string) xml_findAttribute( $v2, "url");
				
					$smarty->assign("id2", $_id);
					$smarty->assign("title2", $_title);
					$smarty->assign("url2", $_url);
				}
			}
		}
	}

}
?>