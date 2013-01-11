<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager count all active visitors
 *
 * Type:     function<br>
 * Name:     sitemanager_count_all_active_visitors<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_count_all_active_visitors($params, &$smarty) {
	
	$all = sql_count_all();
	$visitors = $all - $params["logged"];
	
	
	$smarty->assign("visitors", $visitors);
	
}
?>