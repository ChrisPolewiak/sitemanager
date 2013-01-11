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

function smarty_function_sitemanager_contentnews_contenttext_www_search($params, &$smarty) {

	$smarty->clear_assign("contentnews_search");
	$smarty->clear_assign("contenttext_search");

	$search_contentnews = array();
	$search_contenttext = array();

	$query = $params["www_query"];

	if($result_news = contentnews_www_search($query)){
		while($row_news = mysql_fetch_assoc($result_news)){
			$search_contentnews[] = $row_news;
		}
	}
	
	if($result_text = contenttext_www_search($query)){
		while($row_text = mysql_fetch_assoc($result_text)){
			$search_contenttext[] = $row_text;
		}
	}
	
	$smarty->assign("search_contentnews", $search_contentnews);
	$smarty->assign("search_contenttext", $search_contenttext);
}

?>