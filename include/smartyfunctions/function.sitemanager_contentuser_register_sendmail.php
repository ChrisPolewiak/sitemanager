<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Register sendmail
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_register_sendmail<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_register_sendmail($params, &$smarty) {
	global $SITE_TITLE;
		
		$id_contentuser = $params["id"];
		
		if( $contentuser_reg = contentuser_dane( $id_contentuser ) ) {
		
		$spwd = substr(md5($MD5_PASSW.$contentuser_reg["contentuser_email"]),0,10);
		$confirm_url = "http://".$_SERVER["SERVER_NAME"].$ENGINE."/pl/register_confirmation?a=".$contentuser_reg["id_contentuser"]."&spwd=".$spwd;
	
		$body = "";
		$body .= "Rejestracja konta<br /><br />";
		$body .= "Witaj! <br />";
		$body .= "Twoje konto zostało zarejestrowane w serwisie PZHL, ale nie jest jeszcze aktywne. <br />";
		$body .= "W celu aktywacji konta kliknij na poniższy link lub przekopiuj go do swojej przeglądarki internetowej: <br /><br />";
		$body .= "<a href=\"".$confirm_url."\"><b>".$confirm_url."</b></a> <br /><br />";
		$body .= "Jeżeli nie rejestrowałeś swojego konta w serwisie PZHL i ten mail to pomyłka, zgłoś się do administratora serwisu. <br />";
		$body .= "Wiadomość jest generowana automatycznie. Prosimy na nią nie odpowiadać. <br /><br />";
		$body .= "Pozdrawiamy <br />";
		$body .= "Zespół PZHL";
				
		$sender_name = "Serwis PZHL";
		$sender_email = "pzhl@pzhl.org.pl";
		$recipient_name = $contentuser_reg["contentuser_username"];
		$recipient_email = $contentuser_reg["contentuser_email"];
		$subject = "Potwierdzenie rejestracji konta w serwisie PZHL";
		$txtbody = "";
		$cc = array();
		$bcc = array();
		$files = $files;	
		
		$error = mail_html_default( $sender_name, $sender_email, $recipient_name, $recipient_email, $subject, $body, $txtbody, $cc, $bcc, $files);

		if($error) {
			trigger_error("Mail Sent Error", E_USER_ERROR);
		}
	#	return true;
	}
	return false;
				
}
?>