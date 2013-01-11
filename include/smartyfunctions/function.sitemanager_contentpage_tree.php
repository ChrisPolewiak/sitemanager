<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager tree of contentpage
 *
 * Type:     function<br>
 * Name:     sitemanager_contentpage_tree<br>
 * Purpose:  Generate a tree array of contentpage starting from selected id_contentpage.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_contentpage_tree.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sitemanager_contentpage_tree($params, &$smarty) {
	global $XML_CONTENTPAGE, $ENGINE;

	$ID_CONTENTPAGE = $params["start"];

	if($_xpath = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=$ID_CONTENTPAGE]")) {
		$path = unserialize( stripslashes( (string) $_xpath[0]->path ) );
		$path = array_reverse($path);
		$id_menu = $path[1]["id_contentpage"];
	}
	else {
		$id_menu = $ID_CONTENTPAGE;
	}
	$xpath = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=2]/child::*");
	foreach($xpath AS $v) {
		if($v) {
			$_id = (int) xml_findAttribute( $v, "id_contentpage");
			$_title = (string) xml_findAttribute( $v, "title");
			$_url = $ENGINE."/". (string) xml_findAttribute( $v, "url");
			$ret[] = "<li><a ".($_id==$id_menu?"class=selected":"")." href=\"".$_url."\"><div>".$_title."</div></a></li>";
	        }
	}

	return $ret;

}

?>
