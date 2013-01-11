<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Resize Image
 *
 * Type:     function<br>
 * Name:     sitemanager_image_resize<br>
 * Purpose:  Resize image and return url to new image.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_image_resize.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sitemanager_image_resize($params, &$smarty) {

	$fileurl    = $params["fileurl"];
	$width      = $params["width"];
	$height     = $params["height"];
	$proportion = $params["proportion"];

	return cms_get_foto_resize( $fileurl, $width, $height, $proportion );
}

?>
