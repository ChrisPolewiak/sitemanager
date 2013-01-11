<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Register Confirmation
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_register_confirmation<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_register_confirmation($params, &$smarty) {
	
	$smarty->clear_assign("error");
	
	$a = $params["a"];
	$spwd = $params["spwd"];

	$verify_status = false;
	$verify_again = false;

	if(!$a || !$spwd){
		$ERROR[] = "Brak wymaganych danych do aktywacji konta. Skontaktuj się z administratorem serwisu.";
	}

	if ($a && $spwd) {
		if($tmp_contentuser = contentuser_get_by_username($a)){
			$check = md5($GLOBALS["MD5_PASSW"].$tmp_contentuser["id_contentuser"]);
			if ($check == $spwd) {
				contentuser_status_change( $tmp_contentuser["id_contentuser"], CONTENTUSER_STATUS_ACTIVE );
				$verify_status = true;
			}
			else{
				$ERROR[] = "Aktywacja konta zakończona niepowodzeniem. Skontaktuj się z administratorem serwisu.";
			}
		}
		else {
			$ERROR[] = "Nieznany identyfikator konta.";
		}
	}
	
	$smarty->assign("error", $ERROR);	
	if($verify_status == true){
		$smarty->assign("register_confirmation", 1);
	}
}
?>