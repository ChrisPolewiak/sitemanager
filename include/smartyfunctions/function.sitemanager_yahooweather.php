<?
/**
 * Smarty {sitemanager_yahooweather} function plugin
 *
 * File:     function.sitemanager_yahooweather.php<br>
 * Type:     function<br>
 * Name:     sitemanager_yahooweather<br>
 * Purpose:  Get actual weather info from Yahoo<br>
 * Based on data from http://developer.yahoo.com/weather/<br>
 *
 * @link     http://cms.ocenter.pl/manual/pl/smarty.plugin.sitemanager_yahooweather.php
 * @version  1.0
 * @author
 *
 * @param string (woeid)	// Yahoo Where On Earth ID - http://developer.yahoo.com/geo/geoplanet/guide/concepts.html
 * @return array (weather data)
 */
function smarty_function_sitemanager_yahooweather($params, &$smarty) {

	$weathertext = array(0=>"tornado",1=>"tropikalny sztorm",2=>"huragan",3=>"groźna burza",4=>"burza",5=>"deszcz ze śniegiem",6=>"deszcz ze śniegiem",7=>"śnieg z deszczem",8=>"marznąca mżawka",9=>"mżawka",10=>"marznący deszcz",11=>"przelotny deszcz",12=>"przelotny deszcz",13=>"opad śniegu",14=>"pochmurno z opadami śniegu",15=>"zawieje śnieżne",16=>"śnieg",17=>"grad",18=>"deszcz ze śniegiem",19=>"pył",20=>"mglisto",21=>"mgła",22=>"dymno",23=>"przenikliwo",24=>"wietrznie",25=>"zimno",26=>"mętnie",27=>"pochmurno",28=>"pochmurno",29=>"częściowe zachmurzenie",30=>"częściowe zachmurzenie",31=>"jasna noc",32=>"słonecznie",33=>"zadowalająca",34=>"zadowalająca",35=>"deszcz z gradem",36=>"gorąco",37=>"pojedyncze burze",38=>"rozproszone burze",39=>"rozproszone burze",40=>"przelotne opady",41=>"obfite opady śniegu",42=>"przelotne opady śniegu",43=>"obfite opady śniegu",44=>"pogodnie",45=>"burza z piorunami",46=>"śnieg",47=>"burza z piorunami");

	$woeid = $params["woeid"];

	$XML_WEATHER = simplexml_load_file("http://weather.yahooapis.com/forecastrss?w=".$woeid."&u=c");
	$item = $XML_WEATHER->channel->item;

	$weather["lastBuildDate"] = (string) $XML_WEATHER->channel->lastBuildDate;

	$weather["location"]["city"] = (string) $XML_WEATHER->channel->children('yweather',true)->location->attributes()->city;
	$weather["location"]["region"] = (string) $XML_WEATHER->channel->children('yweather',true)->location->attributes()->region;
	$weather["location"]["country"] = (string) $XML_WEATHER->channel->children('yweather',true)->location->attributes()->country;

	$weather["units"]["temperature"] = (string) $XML_WEATHER->channel->children('yweather',true)->units->attributes()->temperature;
	$weather["units"]["distance"] = (string) $XML_WEATHER->channel->children('yweather',true)->units->attributes()->distance;
	$weather["units"]["pressure"] = (string) $XML_WEATHER->channel->children('yweather',true)->units->attributes()->pressure;
	$weather["units"]["speed"] = (string) $XML_WEATHER->channel->children('yweather',true)->units->attributes()->speed;

	$weather["wind"]["chill"] = (string) $XML_WEATHER->channel->children('yweather',true)->wind->attributes()->chill;
	$weather["wind"]["direction"] = (string) $XML_WEATHER->channel->children('yweather',true)->wind->attributes()->direction;
	$weather["wind"]["speed"] = (string) $XML_WEATHER->channel->children('yweather',true)->wind->attributes()->speed;

	$weather["atmosphere"]["humidity"] = (string) $XML_WEATHER->channel->children('yweather',true)->atmosphere->attributes()->humidity;
	$weather["atmosphere"]["visibility"] = (string) $XML_WEATHER->channel->children('yweather',true)->atmosphere->attributes()->visibility;
	$weather["atmosphere"]["pressure"] = (string) $XML_WEATHER->channel->children('yweather',true)->atmosphere->attributes()->pressure;
	$weather["atmosphere"]["rising"] = (string) $XML_WEATHER->channel->children('yweather',true)->atmosphere->attributes()->rising;

	$weather["astronomy"]["sunrise"] = (string) $XML_WEATHER->channel->children('yweather',true)->astronomy->attributes()->sunrise;
	$weather["astronomy"]["sunset"] = (string) $XML_WEATHER->channel->children('yweather',true)->astronomy->attributes()->sunset;

	$weather["condition"]["text"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->condition->attributes()->text;
	$weather["condition"]["code"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->condition->attributes()->code;
	$weather["condition"]["textpl"] = $weathertext[ $weather["condition"]["code"] ];
	$weather["condition"]["temp"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->condition->attributes()->temp;
	$weather["condition"]["date"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->condition->attributes()->date;

	$weather["description"] = (string) $XML_WEATHER->channel->item->description;

	$weather["forecast"][0]["day"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->day;
	$weather["forecast"][0]["date"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->date;
	$weather["forecast"][0]["low"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->low;
	$weather["forecast"][0]["high"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->high;
	$weather["forecast"][0]["text"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->text;
	$weather["forecast"][0]["code"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[0]->attributes()->code;
	$weather["forecast"][0]["textpl"] = $weathertext[ $weather["forecast"][0]["code"] ];

	$weather["forecast"][1]["day"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->day;
	$weather["forecast"][1]["date"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->date;
	$weather["forecast"][1]["low"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->low;
	$weather["forecast"][1]["high"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->high;
	$weather["forecast"][1]["text"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->text;
	$weather["forecast"][1]["code"] = (string) $XML_WEATHER->channel->item->children('yweather',true)->forecast[1]->attributes()->code;
	$weather["forecast"][1]["textpl"] = $weathertext[ $weather["forecast"][1]["code"] ];

	$smarty->assign("yahooweather", $weather);
}

?>
