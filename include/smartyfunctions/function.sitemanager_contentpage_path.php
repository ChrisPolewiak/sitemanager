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
function smarty_function_sitemanager_contentpage_path($params, &$smarty) {
	global $XML_CONTENTPAGE, $ENGINE;

	$ID_CONTENTPAGE = $params["id"];

	$xpath = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=$ID_CONTENTPAGE]");
	$_path = unserialize( (string) $xpath[0]->path );
	krsort($_path);
	array_shift($_path);

	foreach($_path AS $value) {
		$xpath = $XML_CONTENTPAGE->xpath("//item[@id_contentpage=".$value["id_contentpage"]."]");
		foreach($xpath AS $element) {
			$contentpage_path[] = array(
				"title" => (string) xml_findAttribute( $element, "title" ),
				"url"   => (string) xml_findAttribute( $element, "url" ),
				"id"    => (string) xml_findAttribute( $element, "id_contentpage" ),
			);
		}
	}

	$smarty->assign("contentpage_path", $contentpage_path);
}

?>
