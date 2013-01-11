<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Logout
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_logout<br>
 * Purpose:  Get url of contentpage identified by contentpage name <--- say whaaaaaat? :D
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_logout($params, &$smarty) {
	
	session_unregister("contentuser");
	$smarty->clear_assign("contentuser");
	session_unregister("contentuseracl");
	$smarty->clear_assign("contentuseracl");

	/*
	 * CodeTrigger Injection
	 */
	sitemanager_codetrigger_exec("post:sitemanager_contentuser_logout", array(
		"username"=>$username, "backto"=>$backto,
	));

	SetCookie("autologin", "", time()+60*60*24*365, "/");
	SetCookie("autopassw", "", time()+60*60*24*365, "/");
    
    session_destroy();
    
	header("Location: /");
	exit;	
}

?>
