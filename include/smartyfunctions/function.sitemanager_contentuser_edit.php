<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser edit
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_edit<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_edit($params, &$smarty) {
	global $ERROR;
	
	$smarty->clear_assign("error");
	$smarty->clear_assign("dane");
	
	$ERROR = $params["error"];
	$dane = $params["dane"];
	$id_contentuser = $params["id_contentuser"];
	$dane = trimall($dane);


	if ( ! $dane_prev = contentuser_dane( $id_contentuser ) ) {
		$ERROR = "Błąd systemu - brak danych użytkownika: '$id_contentuser'";
	}
	else {

		if(! $dane["contentuser_email"] || !check_email_valid($dane["contentuser_email"])){
			$ERROR[] = "Podaj prawidłowy email";
		}
		$tmp = contentuser_get_by_email($dane["contentuser_email"]);
		if( $tmp["id_contentuser"] && $tmp["id_contentuser"] != $dane_prev["id_contentuser"] ){
			$ERROR[] = "Podany email jest już zarejestrowany";
		}

		if(is_array($ERROR)){
			$smarty->assign("error", $ERROR);
		}
		else {
			$dane["id_contentuser"] = $id_contentuser;
			foreach($dane AS $k=>$v){
				$dane_prev[$k] = $dane["$k"];
			}
			if ($id_contentuser = contentuser_edit($dane_prev)) {
				$contentuser = contentuser_dane($id_contentuser);
				$smarty->assign("contentuser", $contentuser);
				$_SESSION["contentuser"] = $contentuser;
				$smarty->assign("id_contentuser", $id_contentuser);
				$smarty->assign("form_register_correct", 1);
			}
		}
	}	
}
?>