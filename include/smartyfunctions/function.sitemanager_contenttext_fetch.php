<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Text Content Get
 *
 * Type:     function<br>
 * Name:     sitemanager_get_contenttext<br>
 * Purpose:  Get contenttext object from database.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_get_contenttext.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
function smarty_function_sitemanager_contenttext_fetch($params, &$smarty) {

	$smarty->clear_assign("contenttext");
	$smarty->clear_assign("contenttext_images");
	$smarty->clear_assign("contenttext_files");

	$section_sysname = $params["section_sysname"];
	$id_contentsection = $params["id_contentsection"];
	$id_contentpage = $params["id_contentpage"];
	$contentpage_order = $params["order"];

	if($section_sysname) {
		$result = contenttext_fetch_by_section_sysname($section_sysname, $contentpage_order);
	}
	elseif($id_contentsection) {
		$result = contenttext_fetch_by_section($id_contentsection, $contentpage_order);
	}
	elseif($id_contentpage) {
		$result = contenttext_fetch_by_page($id_contentpage, $contentpage_order);
	}
	
	if ($result) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$contenttext[ $row["id_contenttext"] ] = $row;

			if ($resultimage = contentfileassoc_fetch_by_multiid( $row["id_contenttext"], "contenttext", "Image" ) ) {
				while($rowimage=$resultimage->fetch(PDO::FETCH_ASSOC)) {
					$images[ $row["id_contenttext"] ][] = $rowimage;
				}
				$smarty->assign("contenttext_images", $images);
			}

			if ($resultfile = contentfileassoc_fetch_by_multiid( $row["id_contenttext"], "contenttext", "File" ) ) {
				while($rowfile=$resultfile->fetch(PDO::FETCH_ASSOC)) {
					$files[ $row["id_contenttext"] ][] = $rowfile;
				}
				$smarty->assign("contenttext_files", $files);
			}
		}
		$smarty->assign("contenttext", $contenttext);
			}

}

?>
