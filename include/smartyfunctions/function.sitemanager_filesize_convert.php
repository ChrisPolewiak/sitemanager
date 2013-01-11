<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager Convert filesize
 *
 * Type:     function<br>
 * Name:     sitemanager_filesize_convert<br>
 * Purpose:  Return size in bytes.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_filesize_convert.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_sitemanager_filesize_convert($params, &$smarty) {

	$filesize  = $params["filesize"];
	$stat      = $params["stat"];

	return filesize_convert( $filesize, $stat="" );
}

?>
