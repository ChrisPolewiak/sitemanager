<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser get by email
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_get_by_email<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_get_by_email($params, &$smarty) {
	
	$email = $params["email"];
	
	if($dane = contentuser_get_by_email($email)){
		$smarty->assign("recovery_mail_send", 1);
		$smarty->assign("contentuser_id3", $dane["id_contentuser"]);
	}
	else{
		$ERROR[] = "Nie ma takiego maila w bazie.";
		$smarty->assign("error", $ERROR);
	}	
}
?>