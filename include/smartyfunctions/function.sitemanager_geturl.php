<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager get url by contentpage name
 *
 * Type:     function<br>
 * Name:     sitemanager_geturl<br>
 * Purpose:  Get url of contentpage identified by contentpage name.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_geturl.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sitemanager_geturl($params, &$smarty) {
	return cms_core_geturl_by_name($params["name"]);
}

?>
