<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager contentuser password recovery sendmail
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_password_recovery_sendmail<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */


function smarty_function_sitemanager_contentuser_password_recovery_sendmail($params, &$smarty) {
	global $SITE_TITLE;
		
	$contentuser_email = $params["contentuser_email"];
	$contentuser_username = $params["contentuser_username"];
	$id_contentuser = $params["id_contentuser"];
	$ERROR = $params["error"];

	if($contentuser_email){
		$contentuser = contentuser_get_by_email( $contentuser_email );
	}
	elseif($contentuser_username){
		$contentuser = contentuser_get_by_username( $contentuser_username );
	}
	elseif($id_contentuser){
		$contentuser = contentuser_dane($id_contentuser);
	}
	if( $contentuser ) {

		$confirm_url = "http://".$_SERVER["HTTP_HOST"].cms_core_geturl_by_name("contentuser-password-confirm")."/username=".$contentuser["contentuser_username"]."/code=".md5($GLOBALS["MD5_PASSW"].$contentuser["id_contentuser"]);
		sitemanager_mail(
			$contentmailtemplate_sysname="user-password-recovery",
			$variables=array(
				"data"=>date("Y-m-d"),
				"contentuser_username" => $contentuser["contentuser_username"],
				"contentuser_surname"  => $contentuser["contentuser_surname"],
				"contentuser_firstname"=> $contentuser["contentuser_firstname"],
				"confirm_url"=>$confirm_url,
			),
			$sender_name=$GLOBALS["SERVER_NAME"],
			$sender_email=$GLOBALS["MAIL_ADDR_ADMIN"],
			$recipient_name=$contentuser["contentuser_firstname"]." ".$contentuser["contentuser_surname"],
			$recipient_email=$contentuser["contentuser_email"],
			$subject="",
			$cc="",
			$bcc="",
			$files=""
		);
		$smarty->assign("form_sent", 1);
	}
	else {
		$ERROR[] = "Nieznany użytkownik";
	}
	$smarty->assign("error", $ERROR);
}
?>
