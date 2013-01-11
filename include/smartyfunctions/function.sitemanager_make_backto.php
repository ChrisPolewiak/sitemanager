<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Make Backto
 *
 * Type:     function<br>
 * Name:     sitemanager_make_backto<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_make_backto($params, &$smarty) {

	$backto_ = $params["backto"];

	echo "backto: $backto_ \n";
	
	$smarty->assign("backto", $backto_);
	
}

?>
