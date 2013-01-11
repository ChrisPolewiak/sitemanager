<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager autologin
 *
 * Type:     function<br>
 * Name:     sitemanager_autologin<br>
 * Purpose:  bring joy and happiness to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
 
function smarty_function_sitemanager_autologin($params, &$smarty) {

	if ($_COOKIE["autologin"]!="" && $_COOKIE["autopassw"]!="" && !is_array($_SESSION["contentuser"])){
		if ($tmp_account = contentuser_get_by_username($_COOKIE["autologin"])){
			if( $tmp_account["contentuser_password"] == $_COOKIE["autopassw"] ){
				$contentuser = $tmp_account;
				$_SESSION["contentuser"] = $contentuser;
				header ("Location: /");
				exit;		
			}
		}
	}
}
?>
