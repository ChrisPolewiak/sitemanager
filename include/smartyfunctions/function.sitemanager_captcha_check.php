<?
function smarty_function_sitemanager_captcha_check($params, &$smarty) {

	$ERROR = $params["error"];
	$smarty->clear_assign("error");
	$smarty->clear_assign("captcha_correct");

	$captcha_sent = $params["captcha"];
	$captcha_sess = $_SESSION["captcha_rand_code"];

	if($captcha_sent == $captcha_sess) {
		$smarty->assign("captcha_correct", 1);
	}
	else {
		$ERROR[] = "Nie poprawny kod z obrazka";
		$smarty->assign("captcha_correct", 0);
		$smarty->assign("error", $ERROR);
	}
}
?>
