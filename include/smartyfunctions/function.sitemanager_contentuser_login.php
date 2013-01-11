<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser Login
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_login<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_login($params, &$smarty) {
	global $SM_FRAMEWORK;

	$backto = $_GET["backto"] ? $_GET["backto"] : $_POST["backto"];

	$smarty->clear_assign("error");

	$section_sysname = $params["section_sysname"];
	$id_contentsection = $params["id_contentsection"];
	$id_contentpage = $params["id_contentpage"];

	$username = $params["contentuser_username"];
	$password = $params["contentuser_password"];
	$remember = $params["remember"];

	if(!$username){
		$ERROR[] = "Podaj login";
		$smarty->assign("error", $ERROR);
	}
	if(!$password){
		$ERROR[] = "Podaj hasło";
		$smarty->assign("error", $ERROR);
	}

	if($username && $password){

		$user = contentuser_get_by_username($username);

		if(!$user){
			$ERROR[] = "Nie ma takiego konta w bazie";
		}
		else{
			if(crypt( $password,  $user["contentuser_password"]) != $user["contentuser_password"]){
				$ERROR[] = "Podaj prawidłowe hasło";
			}
			elseif($user["contentuser_status"] != 2){
				$ERROR[] = "Twoje konto jest zablokowanie lub nieaktywowane";
			}
			else{
				$contentuser = $user;
				$_SESSION["contentuser"] = $contentuser;
				
				if(isset($remember)){
					SetCookie("autologin", $username, time()+60*60*24*365, "/");
					SetCookie("autopassw", crypt($password, $user["contentuser_password"]) , time()+60*60*24*365, "/");
				}

				/*
				 * CodeTrigger Injection
				 */
				sitemanager_codetrigger_exec("post:sitemanager_contentuser_login", array(
					"username"=>$username, "backto"=>$backto,
				));

				if($backto) {
					$backto = base64_decode($backto);
					header("Location: ".$backto);
				}
				else {
					header("Location: /");
				}
				exit;             
            }
		}
	}	
	$smarty->assign("error", $ERROR);
}

?>
