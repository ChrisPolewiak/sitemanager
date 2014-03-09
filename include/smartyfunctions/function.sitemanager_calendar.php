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

function smarty_function_sitemanager_calendar($params, &$smarty) {

	$smarty->clear_assign("calendar_current_ym");
	$smarty->clear_assign("calendar_allow_dates");
	$smarty->clear_assign("calendar_weekdays");
	$smarty->clear_assign("calendar_days");

	$current_ym = $params["ym"] ? $params["ym"] : date("Y-m");
	$id_contentnewsgroup = $params["id_contentnewsgroup"];
	
	$today = date("Y-m-j");

	list($current_year, $current_month) = explode("-",$current_ym);

	$starting_weekday = date("w", mktime(0,0,0, $current_month, 1, $current_year));
	$starting_weekday = $starting_weekday ? $starting_weekday : 7;
	$days_in_month = cal_days_in_month ( CAL_GREGORIAN, $current_month, $current_year );

	$calendar_weekdays = array(
		1=>array("name"=>"Poniedziałek", "short"=>"Pn", "class"=>"monday"),
		2=>array("name"=>"Wtorek", "short"=>"Wt", "class"=>"tuesday"),
		3=>array("name"=>"Środa", "short"=>"Śr", "class"=>"wednesday"),
		4=>array("name"=>"Czwartek", "short"=>"Cz", "class"=>"thursday"),
		5=>array("name"=>"Piątek", "short"=>"Pt", "class"=>"friday"),
		6=>array("name"=>"Sobota", "short"=>"So", "class"=>"saturday"),
		7=>array("name"=>"Niedziela", "short"=>"N", "class"=>"sunday"),
	);

	if($result = contentnews_fetch_by_date( $current_year."-".$current_month."-01", $current_year."-".$current_month."-".$days_in_month, $id_contentnewsgroup)){
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$day = date("j", $row["timestamp"]);
			$event[$day][] = $row;
		}
	}

	for($i=1; $i<=24; $i++) {
		$date = date("Y-m", mktime(0,0,0,$i,1,date("Y")));
		$allow_dates[] = $date;
	}

	$d = 0;
	$last_row = false;

	for($i=1;$i<=100;$i++)
	{
		$weekday = ($i-1)%7+1;
		if($i<$starting_weekday) 
		{
			$calendar_days[] = array(
				"value" => "",
				"weekday" => $weekday,
			);
		}
		else {
			$d++;
			if($d>$days_in_month) 
			{
				$last_row=true;
				$calendar_days[] = array(
					"value" => "",
					"weekday" => $weekday,
				);
			}
			else 
			{
				if($event[$d])
				{
					$calendar_days[] = array(
						"value" => $d,
						"weekday" => $weekday,
						"action" => $current_year."-".$current_month."-".$d,
					);
				}
				else 
				{
					$calendar_days[] = array(
						"value" => $d,
						"weekday" => $weekday,
					);
				}
			}
		}
		if($i%7==0) 
		{
			if($last_row)
			break;
		}
	}
	
	foreach ($calendar_days as &$day)
	{
		$currentDay = $current_ym.'-'.$day['value'];
		if ( $currentDay == $today )
		{
			$day['class'] = 'active';
			unset($day);
			break;
		}
	}

	$smarty->assign("calendar_current_ym", $current_ym);
	$smarty->assign("calendar_allow_dates", $allow_dates);
	$smarty->assign("calendar_weekdays", $calendar_weekdays);
	$smarty->assign("calendar_days", $calendar_days);
}

?>
