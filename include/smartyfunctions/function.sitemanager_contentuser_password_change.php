<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager contentuser password change
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_password_change<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_password_change($params, &$smarty) {
	global $ERROR;

	$id_contentuser = $params["id_contentuser"];
	$password_new   = $params["password_new"];
	$password_verify = $params["password_verify"];
	$password_prev   = $params["password_prev"];
	$contentuser_username   = $params["contentuser_username"];
	

	if($contentuser_username) {
		$dane = contentuser_get_by_username($contentuser_username);
	}
	else {
		$dane = contentuser_dane($id_contentuser);
	}

	if( $dane ) {
		if ( isset($params["password_prev"])) {
			if( crypt($password_prev, $dane["contentuser_password"]) != $dane["contentuser_password"]) {
				$ERROR[] = "Podaj poprawnie stare hasło";
				}
		}
		contentuser_password_check( $password_new, $password_verify, $dane["contentuser_username"] );
		if(!is_array($ERROR)){
			$password = crypt($password_new);
			contentuser_password_change($dane["id_contentuser"], $password);
			$smarty->assign("password_changed", 1);
		}
	}
	else {
		$ERROR[] = "Brak danych użytkownika";
	}
	$smarty->assign("error", $ERROR);
}
?>
