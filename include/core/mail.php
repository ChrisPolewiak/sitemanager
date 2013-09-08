<?
/**
 * mail
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	mail
 */


require "Mail.php";
require "Mail/mime.php";

/**
 * @category	mail
 * @package		core
 * @version		5.0.0
*/
function mail_default( $to, $subj, $body, $from, $bcc="", $header="", $footer="" ) {
	global  $MAIL_HEADER,
		$MAIL_FOOTER,
		$MAIL_HEADER_STAFF,
		$MAIL_FOOTER_STAFF,
		$MAIL_HDRS;

	if (is_array($bcc)) {
		$bcc = join(",",$bcc);
	}
	$header = $header ? $header : $MAIL_HEADER;
	$footer = $footer ? $footer : $MAIL_FOOTER;

	$hdrs  = $MAIL_HDRS;
	$hdrs .= "From: ".$from;
	$hdrs .= ($bcc ? "\nBCC:".$bcc : "");

	mail($to, "=?UTF-8?Q?".str_replace(" ","_",imap_8bit($subj))."?=", $header.$body.$footer, $hdrs, "-f".$from);
}

/**
 * @category	mail
 * @package		core
 * @version		5.0.1
*/
function mail_html_default( $sender_name, $sender_email, $recipient_name, $recipient_email, $subject, $htmlbody, $txtbody, $cc, $bcc, $files) {

	// konwersja do ISO
	$subject   = "=?UTF-8?B?". base64_encode( $subject )."?=";
	$htmlbody = $htmlbody;
	$txtbody  = $txtbody;
	$from      = "\"=?UTF-8?B?". base64_encode( $sender_name )."?=\"";
	$from     .= " <". $sender_email .">";
	$to	= "\"=?UTF-8?B?". base64_encode( $recipient_name )."?=\"";
	$to       .= " <". $recipient_email .">";

	$arrMailEncoding = array (
		"html_charset" => "UTF-8",
		"text_charset" => "UTF-8",
		"head_charset" => "UTF-8",
	);
	$xmailer  = $SOFTWARE_INFORMATION["application"];
	$xmailer .= " v. ".$SOFTWARE_INFORMATION["version"];
	$xmailer .= " (plugin: ".$PLUGIN_CONFIG["shop"]["name"]." v.".$PLUGIN_CONFIG["shop"]["version"].")";

	$return_to = $sender_email;
	$recipients = array($to);

	// naglowki
	$arrHeaders = array(
		"From"			=> $from,
		"Return-Path"	=> $return_to,
		"Subject"		=> $subject,
		"X-Mailer"		=> $xmailer,
	);

	$mail_backend = $GLOBALS["SM_MAIL_BACKEND"] ? $GLOBALS["SM_MAIL_BACKEND"] : "mail";
	if($mail_backend == "smtp") {
		$arrSmtpConfig = array (
			"From"		=> $sender_email,
			"host"		=> $GLOBALS["SM_SMTP_HOST"]
		);
	}
	else {
		$arrSmtpConfig = array (
			"sendmail_args" => "-f".$return_to,
		);
	}

	$objMail = Mail::factory ( $mail_backend, $arrSmtpConfig );
	$objMime = new Mail_mime ( "\n" );

	if(is_array($files)){
		foreach ($files AS $fileid=>$file) {
			if($file["disposition"] == "attachment"){
				$objMime->addAttachment(
					$file["filepath"],
					$file["contenttype"],
					$fileid,
					$isfile = true );
			}
			else {
				$objMime->addHTMLImage( 
					$file["filepath"],
					$file["contenttype"],
					$fileid,
					$isfile = true );
			}
		}
	}

	if($txtbody) {
		$objMime->setTXTBody ($txtbody);
	}
	if($htmlbody) {
		$objMime->setHTMLBody ($htmlbody);
	}

	$mailBody = $objMime->get ( $arrMailEncoding );
	$arrHeaders = $objMime->headers ( $arrHeaders );
	$objError = $objMail->send ( $recipients, $arrHeaders, $mailBody );

	if(is_array($objError)){
		return $objError;
	}
	else {
		return 0;
	}
}

/**
 * sitemanager_mail
 *
 * @param	$content_mailtemplate__sysname  string     nazwa systemowa szablonu (content_mailtemplate)
 * @param	$variables                    array   zmienne do podstawienia
 *	$variables = array(
 *		"imie" => "Jan",
 *		"nazwisko" => "Kowalski",
 *		"array" => array(
 *			array("nazwa" => "produkt1", "cena" => "123.00"),
 *			array("nazwa" => "produkt2", "cena" => "234.00"),
 *			array("nazwa" => "produkt3", "cena" => "345.00"),
 *		),
 *	);
 * @param	$sender_name                  string  nazwa nadawcy
 * @param	$sender_email                 string  adres e-mail nadawcy
 * @param	$recipient_name               string  nazwa odbiorcy
 * @param	$recipient_email              string  adres e-mail odbiorcy
 * @param	$subject                      string  temat wiadomosci
 * @param	$cc                           array   lista odbiorców CC
 * @param	$bcc                          array   lista odbiorców BCC
 * @param	$files                        array   lista dodatkowych za³¹czników
 *                               - disposition (attachment|inline)
 *                               - filepath
 *                               - contenttype
 *  
 * @category	mail
 * @package		core
 * @version		5.0.2
*/
function sitemanager_mail( $content_mailtemplate__sysname, $variables, $sender_name, $sender_email, $recipient_name, $recipient_email, $subject, $cc, $bcc, $files) {
	global $ROOT_DIR;

	if( ! $content_mailtemplate = content_mailtemplate_get_by_sysname( $content_mailtemplate__sysname )) {
		return 0;
	}
	$subject = $subject ? $subject : $content_mailtemplate["content_mailtemplate__name"];

	require_once(SMARTY_DIR."/Smarty.class.php");
	$smarty_mail = new Smarty();
	$smarty_mail->template_dir  = SMARTY_TEMPLATES."/".$SITESELECTED."/template";
	$smarty_mail->compile_dir   = SMARTY_SITEMANAGER_DIR."/templates_c/";
	$smarty_mail->setCaching(Smarty::CACHING_OFF);
	$smarty_mail->assign("template", $variables);

	$textbody = $smarty_mail->fetch("string:".$content_mailtemplate["content_mailtemplate__textbody"]);
	$textbody = strip_tags($textbody);
	$htmlbody = $smarty_mail->fetch("string:".$content_mailtemplate["content_mailtemplate__htmlbody"]);

	// fetch inline images from template
	$regexp = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>";
	if(preg_match_all("/$regexp/siU", $htmlbody, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match) {
			$match = trimall($match);
			$_url = htmlentities($match[2]);
			$uniqurl[$_url] = 1;
		}
	}

	$senderaddr_from_template = $content_mailtemplate["content_mailtemplate__sender_email"];
	if( preg_match("/{\\\$template\.(.+)}/i", $senderaddr_from_template, $tmp) ) {
		$senderaddr_from_template = $variables["mailform"][$tmp[1]];
	}
	$sender_email = $senderaddr_from_template ? $senderaddr_from_template : $sender_email;


	$sendername_from_template = $content_mailtemplate["content_mailtemplate__sender_name"];
	if( preg_match("/{\\\$template\.(.+)}/i", $sendername_from_template, $tmp) ) {
		$sendername_from_template = $variables["mailform"][$tmp[1]];
	}
	$sender_name = $sendername_from_template ? $sendername_from_template : $sender_name;

	$subject_from_template = $content_mailtemplate["content_mailtemplate__subject"];
	if( preg_match("/{\\\$template\.(.+)}/i", $subject_from_template, $tmp) ) {
		$subject_from_template = $variables["mailform"][$tmp[1]];
	}
	$subject = $subject_from_template ? $subject_from_template : $subject;

	$subject   = "=?UTF-8?B?". base64_encode( $subject )."?=";

	$from      = "=?UTF-8?B?". base64_encode( $sender_name ) ."?=";
	$from     .= "<". $sender_email .">";

	$from      = "<". $sender_email .">";

	$arrMailEncoding = array (
		"html_charset" => "UTF-8",
		"text_charset" => "UTF-8",
		"head_charset" => "UTF-8",
	);

	$xmailer  = $SOFTWARE_INFORMATION["application"];
	$xmailer .= " v. ".$SOFTWARE_INFORMATION["version"];

	$return_to = $sender_email;

	$mail_backend = $GLOBALS["SM_MAIL_BACKEND"] ? $GLOBALS["SM_MAIL_BACKEND"] : "mail";
	if($mail_backend == "smtp") {
		$arrSmtpConfig = array (
			"From"		=> $sender_email,
			"host"		=> $GLOBALS["SM_SMTP_HOST"]
		);
	}
	else {
		$arrSmtpConfig = array (
			"sendmail_args" => "-f".$return_to,
		);
	}

	// naglowki
	$arrHeaders = array(
		"From"			=> $from,
		"To"			=> $recipient_email,
		"Date"			=> date("r"),
		"Return-Path"	=> $return_to,
		"Subject"		=> $subject,
		"X-Mailer"		=> $xmailer,
		"Message-ID"	=> "<" . $_SERVER["REQUEST_TIME"] . md5($_SERVER["REQUEST_TIME"]) . "@" . $_SERVER["HOSTNAME"] . ">",
	);

	if($cc) {
		$arrHeaders["CC"] = $cc;
	}
	if($bcc) {
		$arrHeaders["BCC"] = $bcc;
	}

	$objMail = Mail::factory ( $mail_backend, $arrSmtpConfig );
	$objMime = new Mail_mime ( "\n" );

	// add images to mail
	if(is_array($uniqurl)) {
		foreach($uniqurl AS $imageurl=>$null) {

			if(! preg_match("/^(http|ftp)/", $imageurl) ) {
				$file_imageurl = $ROOT_DIR."/html".$imageurl;
			}
			else {
				$imageurl = file_get_contents( $imageurl );
			}
			$file_uuid = uuid();
			$img_name_slashed = preg_replace("/(\/)/", "\/", $imageurl);
			$html_image_search[]  = "/".$img_name_slashed."/";
			$html_image_replace[] = $file_uuid;
			$objMime->addHTMLImage( $file_imageurl, "", $file_uuid, $isfile=true );
		}
		$htmlbody = preg_replace($html_image_search, $html_image_replace, $htmlbody);
	}
	
	if (isset($_FILES["mailform_files"])) {
		
		global $MIME_TYPES;
		$filesArray = $_FILES["mailform_files"];
		$filesCounter2 = 0;
		foreach ($filesArray as $key => $value) {
			foreach ($value as $val) {
				$tempFiles[$filesCounter2][$key] = $val;
				$filesCounter2++;
			}
			$filesCounter2 = 0;
		}
		$tempDir = $ROOT_DIR."/temp";
		
		rmdir($tempDir);
		mkdir($tempDir, 0777);
		foreach ($tempFiles as $key => $value) {
			$filesToDelete[] = $tempDir."/".$tempFiles[$key]["name"];
			move_uploaded_file($tempFiles[$key]["tmp_name"], $filesToDelete[$key]);
			
			$files[$key]["disposition"] = "attachment";
			$files[$key]["filepath"] = $filesToDelete[$key];
			$files[$key]["contenttype"] = $tempFiles[$key]["type"];
			$files[$key]["filename"] = $tempFiles[$key]["name"];
		}
		
	}
	
	if(is_array($files)) {
		foreach ($files AS $fileid=>$file) {
			if($file["disposition"] == "attachment") {
				$objMime->addAttachment(
					$file["filepath"],
					$file["contenttype"],
					$file["filename"] ? $file["filename"] : $fileid,
					$isfile = true );
			}
			else {
				$objMime->addHTMLImage( 
					$file["filepath"],
					$file["contenttype"],
					$fileid,
					$isfile = true );
			}
		}
	}
	
	

	if($textbody) {
		$objMime->setTXTBody ($textbody);
	}
	if($htmlbody) {
		$objMime->setHTMLBody ($htmlbody);
	}

	if($result = content_mailtemplate2content_user_fetch_by_content_mailtemplate($content_mailtemplate["content_mailtemplate__id"])) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$recipients_from_template[] = array(
				"name" => "",
				"addr" => $row["content_user__username"],
			);
		}
	}
	if(is_array($recipients_from_template)) {
		foreach($recipients_from_template AS $recipient) {
			$to  = "=?UTF-8?B?". base64_encode( $recipient["name"] ) ."?=";
			$to .= "<". $recipient["addr"] .">";
			$recipients[] = $to;
		}
	}
	else {
		$to  = "=?UTF-8?B?". base64_encode( $recipient_name )."?=";
		$to .= "<". $recipient_email .">";
		$recipients = array($to);
	}

	foreach($recipients AS $recipient) {

		$mailBody = $objMime->get ( $arrMailEncoding );
		$arrHeaders = $objMime->headers ( $arrHeaders );
		$objError = $objMail->send ( $recipient, $arrHeaders, $mailBody );
	}

	if(is_array($objError)){
		return $objError;
	}
	else {
		return 1;
	}
}

?>
