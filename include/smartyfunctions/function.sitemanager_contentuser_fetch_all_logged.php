<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Contentuser fetch all logged
 *
 * Type:     function<br>
 * Name:     sitemanager_contentuser_fetch_all_logged<br>
 * Purpose:  bring joy and happines to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_contentuser_fetch_all_logged($params, &$smarty) {
	
	$logged_users = 0;
	
	if ($result=adminsession_whoisonline()){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$data=root_session_decode(stripslashes($row["SessionData"]));
			if($data["contentuser"]["id_contentuser"] == $_SESSION["contentuser"]["id_contentuser"] && $data["contentuser"]["contentuser_username"]) {
				$logged_users++;
			}
		}
	}
	
	$smarty->assign("logged_users", $logged_users);
	
}
?>