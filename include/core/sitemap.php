<?
/**
 * sitemap
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	sitemap
 */

/**
 * @category	sitemap
 * @package		core
 * @version		5.0.0
*/
function sm_core_sitemap() {
	global $SOFTWARE_INFORMATION, $SERVER_NAME, $CONTENTPAGE, $ENGINE;

	$xml  = "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
	$xml .= "<!-- generator=\"".$SOFTWARE_INFORMATION["author"]." ".$SOFTWARE_INFORMATION["application"]." v.".$SOFTWARE_INFORMATION["version"]."\" -->\n";
	$xml .= "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";
	$xml .= "  <url>\n";
	$xml .= "    <loc>http://".$_SERVER["HTTP_HOST"]."</loc>\n";
	$xml .= "    <lastmod>". gmdate("Y-m-d") ."</lastmod>\n";
	$xml .= "    <changefreq>weekly</changefreq>\n";
	$xml .= "    <priority>1</priority>\n";
	$xml .= "  </url>\n";

	foreach($CONTENTPAGE AS $_cpage) {
		if($_cpage["order"]) {
			if( preg_match( "/sitemap_disabled=1/", $_cpage["field_contentpage_params"] ) )
				continue;
			$xml .= "  <url>\n";
			$xml .= "    <loc>http://".$_SERVER["HTTP_HOST"].$ENGINE."/".$_cpage["url"]."</loc>\n";
			$xml .= "    <lastmod>". gmdate("Y-m-d") ."</lastmod>\n";
			$xml .= "    <changefreq>weekly</changefreq>\n";
			$xml .= "    <priority>0</priority>\n";
			$xml .= "  </url>\n";
		}
	}

	$xml .= "</urlset>";

	return $xml;
}

?>
