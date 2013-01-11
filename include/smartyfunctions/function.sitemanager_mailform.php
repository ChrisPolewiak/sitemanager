<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Mailform
 *
 * Type:     function<br>
 * Name:     sitemanager_mailform<br>
 * Purpose:  Send data from web form by template. 
 * @link 
 * @author   Chris Polewiak
 * @param array (form elements)
 * @return tinyint (status)
 */
 
function smarty_function_sitemanager_mailform($params, &$smarty) {

	$contentmailtemplate_sysname = $params["template"];
	$mailform = $params["mailform"];
	$id_contentuser = $_GLOBAL["contentuser"]["id_contentuser"];
	
	$sender_name = $param["sender_name"];
	$sender_email = $param["sender_email"];
	$recipient_name = $param["recipient_name"];
	$recipient_email = $param["recipient_email"];
	$subject = $param["subject"];
	$cc = $param["cc"];
	$bcc = $param["bcc"];
	$files = $param["files"];	
	$smarty->assign("mailform_result", 0);

	if($id_contentuser) {
		$contentuser = contentuser_get($id_contentuser);
	}

	$variables=array(
		"data" => date("Y-m-d H:i:s"),
		"contentuser" => $contentuser,
		"mailform" => $mailform,
	);

	if(sitemanager_mail( $contentmailtemplate_sysname, $variables, $sender_name, $sender_email, $recipient_name, $recipient_email, $subject, $cc, $bcc, $files)) {
		$smarty->assign("mailform_result", 0);
	}
	else {
		$smarty->assign("mailform_result", 1);
	}
}
?>
