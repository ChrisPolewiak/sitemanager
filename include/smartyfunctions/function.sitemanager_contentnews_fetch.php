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
function smarty_function_sitemanager_contentnews_fetch($params, &$smarty) {

	$smarty->clear_assign("contentnews");
	$smarty->clear_assign("contentnews_images");
	$smarty->clear_assign("contentnews_files");

	$id_contentnewsgroup = $params["id_contentnewsgroup"];
	$from  = $params["from"];
	$limit = $params["limit"];

	if ($result = contentnews_fetch_by_contentnewsgroup($id_contentnewsgroup, 0, $from, $limit)) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			$counter++;
			$row["contentnews_datetime"] = date_parse($row["contentnews_datetime"]);
			$news[ $row["id_contentnews"] ] = $row;

			if ($result_files = contentfileassoc_fetch_by_multiid( $row["id_contentnews"], "contentnews", "image" ) ) {
				while($row_files=$result_files->fetch(PDO::FETCH_ASSOC)) {
					$images[ $row["id_contentnews"] ][] = $row_files;
				}
							}

			if ($result_files = contentfileassoc_fetch_by_multiid( $row["id_contentnews"], "contentnews", "File" ) ) {
				while($row_files=$result_files->fetch(PDO::FETCH_ASSOC)) {
					$files[ $row["id_contentnews"] ][] = $row_files;
									}
			}
		}
		
		$smarty->assign("contentnews", $news);
		$smarty->assign("contentnews_images", $images);
		$smarty->assign("contentnews_files", $files);
#		$smarty->assign("counter", $counter);
		
			}
}

?>
