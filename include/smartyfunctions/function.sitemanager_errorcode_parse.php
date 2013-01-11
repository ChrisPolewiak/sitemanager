<?

function smarty_function_sitemanager_errorcode_parse($params, &$smarty) {
	global $ERROR_CODES;

        $smarty->clear_assign("errormsg");

	$lang = $params["lang"];
	$errorcode = $smarty->_tpl_vars["errorcode"];
	
	foreach($errorcode AS $code) {
		if($ERROR_CODES[$code]["msg"][$lang])
			$errormsg[] = $ERROR_CODES[$code]["msg"][$lang];
		else
			$errormsg[] = $ERROR_CODES[$code]["msg"]["en_us"];
	}
	$smarty->assign("errormsg", $errormsg);
}

?>
