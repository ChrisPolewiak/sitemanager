<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Register
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_register<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_register($params, &$smarty) {
	global $ERROR;

	$smarty->clear_assign("error");
	$smarty->clear_assign("dane");

	$ERROR = $params["error"];
	$dane = $params["dane"];
	$groups = $params["groups"];

	$dane = trimall($dane);

	if(strlen($dane["contentuser_username"])<5) {
		$ERROR[] = "Podany login jest zbyt krótki, podaj min 5 znaków";
	}
	elseif(!preg_match("/^[\w\d\_\-]+$/i", $dane["contentuser_username"])) {
		$ERROR[] = "Identyfikator konta może zawierać wyłącznie litery, cyfry oraz znaki '_', '-'. Nie może zawierać polskich liter.";
	}
	if(! $dane["contentuser_password"]){
		$ERROR[] = "Podaj hasło";
	}
	if(! $dane["contentuser_email"] || !check_email_valid($dane["contentuser_email"])){
		$ERROR[] = "Podaj prawidłowy email";
	}
	contentuser_password_check( $dane["contentuser_password"], $dane["contentuser_password2"], $dane["contentuser_username"] );

	if( $dane["contentuser_password"] != $dane["contentuser_password2"] ){
		$ERROR[] = "Powtórzone hasło nie zgadza się z oryginalnym";
	}

	if( contentuser_get_by_username($dane["contentuser_username"]) ){
		$ERROR[] = "Użytkownik o podanym loginie jest już zarejestrowany";
	}
	if( contentuser_get_by_email($dane["contentuser_email"]) ){
		$ERROR[] = "Podany email jest już zarejestrowany";
	}

	if(is_array($ERROR)){
		$smarty->assign("error", $ERROR);
	}
	else {
		$groups = split(",",$groups);
		$dane["contentuser_status"]=CONTENTUSER_STATUS_NEW;
		$id_contentuser = contentuser_add($dane);
		if($id_contentuser) {

			$confirm_url = "http://".$_SERVER["HTTP_HOST"].cms_core_geturl_by_name("contentuser-register-confirm")."/username=".$dane["contentuser_username"]."/code=".md5($GLOBALS["MD5_PASSW"].$id_contentuser);
			sitemanager_mail( 
				$contentmailtemplate_sysname="user-register-confirm",
				$variables=array(
					"data"=>date("Y-m-d"),
					"contentuser_username"=>$dane["contentuser_username"],
					"contentuser_password"=>$dane["contentuser_password"],
					"contentuser_surname"=>$dane["contentuser_surname"],
					"contentuser_firstname"=>$dane["contentuser_firstname"],
					"confirm_url"=>$confirm_url,
				),
				$sender_name=$GLOBALS["SERVER_NAME"],
				$sender_email=$GLOBALS["MAIL_ADDR_ADMIN"],
				$recipient_name=$dane["contentuser_firstname"]." ".$dane["contentuser_surname"],
				$recipient_email=$dane["contentuser_email"],
				$subject="",
				$cc="",
				$bcc="",
				$files=""
			);
			if($groups) {
				foreach($groups AS $id_contentusergroup) {
					contentuser2contentusergroup_edit( $id_contentuser, $id_contentusergroup );
				}
			}
		}
		$smarty->assign("id_contentuser", $id_contentuser);
		$smarty->assign("form_register_correct", 1);
	}	
}
?>