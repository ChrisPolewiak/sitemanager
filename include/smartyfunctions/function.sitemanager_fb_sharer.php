<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */

/**
 * Smarty Sitemanager tree of contentpage
 *
 * Type:     function<br>
 * Name:     sitemanager_contentpage_tree<br>
 * Purpose:  Generate a tree array of contentpage starting from selected id_contentpage.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_contentpage_tree.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return string
 */
 
function smarty_function_sitemanager_fb_sharer($params, &$smarty) {
	$id = $params['id'];
	$page = $params['page'];
	/*
	echo '<pre>';
	var_dump($_SERVER);
	echo '</pre>';*/
	
	if ( $page == 'index' )
	{
		$page = 'aktualnosci';
	}
	
	$link = 'http://'.$_SERVER['HTTP_HOST'].'/pl/'.$page.'?id='.$id;
	$fbLink = 'http://www.facebook.com/sharer.php?u='.rawurlencode($link);

	return $fbLink;	
	
}

?>
