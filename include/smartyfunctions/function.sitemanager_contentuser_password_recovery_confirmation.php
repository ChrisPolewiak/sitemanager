<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Password Change Confirmation
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_password_change_confirmation<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_password_recovery_confirmation($params, &$smarty) {
	
	$smarty->clear_assign("error");
	
	$contentuser_username = $params["contentuser_username"];
	$code = $params["code"];

	if(!$contentuser_username || !$code){
		$ERROR[] = "Brak wymaganych danych do zmiany hasła. Skontaktuj się z administratorem serwisu.";
	}
	else {
		if($tmp_contentuser = contentuser_get_by_username($contentuser_username)){
			$check = md5($GLOBALS["MD5_PASSW"].$tmp_contentuser["id_contentuser"]);
			if ($check == $code) {
				$smarty->assign("password_recovery_confirmation", 1);
			}
			else{
				$ERROR[] = "Niepoprawna weryfikacja kodu, wprowadź adres poprawnie.";
			}
		}
	}
	$smarty->assign("error", $ERROR);	
}
?>
