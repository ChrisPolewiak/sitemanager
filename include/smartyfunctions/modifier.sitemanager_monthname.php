<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */

/**
 * Smarty Sitemanager return month name by number
 *
 * Type:     modifier<br>
 * Name:     sitemanager_monthname<br>
 * Purpose:  Return Month name based on numer and array with names.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_monthname.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param integer
 * @return string|null if error
 */
function smarty_modifier_sitemanager_monthname($month) {

	global $MONTH_NAMES;


	if(is_array($MONTH_NAMES)){
		if($MONTH_NAMES[$month]){
			return $MONTH_NAMES[$month];
		}
	}
	return "";
}

?>
