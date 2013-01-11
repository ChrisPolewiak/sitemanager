<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser get
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_get<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_get($params, &$smarty) {
	
	$id = $params["id"];
	
	$dane = contentuser_dane($id);
	
	$smarty->assign("dane_user", $dane);
	
}
?>