<?
/**
 * Smarty plugin
 * @package Sitemanager
 * @subpackage plugins
 */


/**
 * Smarty Sitemanager calendar
 *
 * Type:     function<br>
 * Name:     sitemanager_calendar<br>
 * Purpose:  Fetch contentnews as calendar and build calendar data.
 * @link http://sitemanager.polewiak.pl/manual/pl/smarty.plugin.sitemanager_calendar.php
 * @author   Chris Polewiak <chris@polewiak.pl>
 * @param array
 * @param Smarty
 * @return array|null if not found any data
 */
function smarty_function_sitemanager_calendar_fetch($params, &$smarty) {

	$smarty->clear_assign("events");

	$date = $params["date"];
	$id_contentnewsgroup = $params["group"];

	if($result = contentnews_fetch_by_date( $date, $date, $id_contentnewsgroup ) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$events[] = $row;
		}
		$smarty->assign("events", $events);
	}
}

?>
