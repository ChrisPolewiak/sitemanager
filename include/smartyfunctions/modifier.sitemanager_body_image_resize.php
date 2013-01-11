<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */

/**
 * Smarty Sitemanager resize all images in html string
 *
 * Type:     modifier<br>
 * Name:     sitemanager_body_image_resize<br>
 * Purpose:  Resize all images in html string.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_body_image_resize.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param string
 * @return string
 */
function smarty_modifier_sitemanager_body_image_resize( $body ) {

	return cms_text_image_resize( $body );
}

?>
