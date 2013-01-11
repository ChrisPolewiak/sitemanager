<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Fetch contentnews
 *
 * Type:     function<br>
 * Name:     sitemanager_fetch_contentnews<br>
 * Purpose:  Fetch contentnews objects from database.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_fetch_contentnews.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
function smarty_function_sitemanager_contentnews_count($params, &$smarty) {

	$id_contentnewsgroup = $params["id_contentnewsgroup"];
	$from  = $params["from"];
	$limit = $params["limit"];

	if ($result = contentnews_fetch_by_contentnewsgroup($id_contentnewsgroup, 0, $from, $limit)) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$row["contentnews_datetime"] = date_parse($row["contentnews_datetime"]);
			$news[ $row["id_contentnews"] ] = $row;

		}
			
		$counter = 0;
		
		foreach($news as $k=>$v){
			$counter++;
		}
		
		$np = $counter / 15;
		$news_pages = ceil($np) - 1;		
		$smarty->assign("news_pages", $news_pages);		
			}
		
}

?>
