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
function smarty_function_sitemanager_youtube_get_last_video_from_channel($params, &$smarty) {
	$channel = $params['userName'];

	// create a new cURL resource
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://gdata.youtube.com/feeds/api/users/".$channel."/uploads?max-results=1&alt=json&lr=pl");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	// grab URL and pass it to the browser
	$result = curl_exec($ch);
	
	// close cURL resource, and free up system resources
	curl_close($ch);
	
	$array = json_decode($result, TRUE);
	
	$explode = explode('/',$array['feed']['entry']['0']['id']['$t']);
	$count = (count($explode) - 1);
	$id = $explode[$count];
	
	//return '<a href="http://www.youtube.com/watch?v='.$id.'"><img src="http://img.youtube.com/vi/'.$id.'/0.jpg" alt="" width="185"></a>';
	return '<iframe width="195" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';	
	
}

?>
