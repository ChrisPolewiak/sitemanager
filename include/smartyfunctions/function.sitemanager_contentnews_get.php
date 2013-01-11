<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager contentnews get
 *
 * Type:     function<br>
 * Name:     sitemanager_contentnews_get<br>
 * Purpose:  get news details.
 * @link google.pl
 * @author   null
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
function smarty_function_sitemanager_contentnews_get($params, &$smarty) {

	$smarty->clear_assign("contentnews");
	$smarty->clear_assign("contentnews_images");
	$smarty->clear_assign("contentnews_files");

	$id_contentnews = $params["id_contentnews"];


	if ($result = contentnews_get($id_contentnews)) {
	
			$result["contentnews_datetime"] = date_parse($result["contentnews_datetime"]);

			if ($result_files = contentfileassoc_fetch_by_multiid( $result["id_contentnews"], "contentnews", "Image" ) ) {
				while($row_files=$result_files->fetch(PDO::FETCH_ASSOC)) {
					$images[ $result["id_contentnews"] ][] = $row_files;
				}
			}

			if ($result_files = contentfileassoc_fetch_by_multiid( $result["id_contentnews"], "contentnews", "File" ) ) {				
				while($row_files=$result_files->fetch(PDO::FETCH_ASSOC)) {
					$files[ $result["id_contentnews"] ][] = $row_files;
				}
			}
	
		
		
		$smarty->assign("contentnews", $result);
		$smarty->assign("contentnews_images", $images);
		$smarty->assign("contentnews_files", $files);
		
			}
}

?>
