<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager contentnews contenttext www search   
 *
 * Type:     function<br>
 * Name:     sitemanager_contentnews_contenttext_www_search<br>
 * Purpose:  bring joy and happiness to the world.
 * @link http://google.pl
 * @author   Chuck Norris
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */

function smarty_function_sitemanager_contentextra_get_elements($params, &$smarty) {

	$smarty->clear_assign("contentextralist_values");
	$smarty->clear_assign("contentextralist_names");

	$object_name = $params["object"];
	$dbname_name = $params["element"];


	if( $result=contentextra_fetch_by_object($object_name) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			if($row["contentextra_dbname"]==$dbname_name) {
				$id_contentextra = $row["id_contentextra"];
			}
		}

		if($id_contentextra) {
			if( $result=contentextralist_fetch_by_contentextra($id_contentextra) ) {
				while($row=$result->fetch(PDO::FETCH_ASSOC)) {
					$contentextralist_values[]=$row["contentextralist_value"];
					$contentextralist_names[]=$row["contentextralist_name"];
				}
			}
		}
	}
	$smarty->assign("contentextralist_values", $contentextralist_values);
	$smarty->assign("contentextralist_names", $contentextralist_names);
}

?>
