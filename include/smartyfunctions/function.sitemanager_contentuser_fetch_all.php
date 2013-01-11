<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser fetch all
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_fetch_all<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_fetch_all($params, &$smarty) {
	
	$registered_users = contentuser_fetch_all_count();
	
	$smarty->assign("registered_users", $registered_users);
	
}
?>