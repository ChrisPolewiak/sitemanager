<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser get by username
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_get_by_username<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_get_by_username($params, &$smarty) {
	
	$username = $params["username"];
	
	if($dane = contentuser_get_by_username($username)){
		$email = $dane["contentuser_email"];		
		$smarty->assign("contentuser_email", $email);
	}
	else{
		return 0;
	}	
}
?>