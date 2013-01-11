<?
/**
 * translation
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	translation
 */

$SM_TRANSLATION_LANGUAGES = array(
	"en" => "English",
	"pl" => "Polski",
);

$LANG = "pl";

$data = parse_ini_file($INCLUDE_DIR."/lang/".$LANG.".ini.php") or die("parse lang error");
foreach($data AS $k=>$v) {
	$k = strtoupper($k);
	if ($k!="_DEFINE_") {
		$SM_TRANSLATION["CORE"][$k] = $v;
	}
}
unset($data);

/**
 * @category	translation
 * @package		core
 * @version		5.0.0
*/
function __($schema = "core", $phrase ) {
	global $SM_TRANSLATION;

	$schema = strtoupper($schema);
	$phrase = strtoupper($phrase);
	$fnargs = func_get_args();

	if( sizeof($fnargs)>2 && isset($SM_TRANSLATION[$schema][$phrase]) ) {
		array_shift($fnargs);
		array_shift($fnargs);
		eval ("\$phrase = sprintf( \"".$SM_TRANSLATION[$schema][$phrase]."\", \"".join( "\",\"" ,$fnargs)."\"); ");
		return $phrase;
	}
	else {
		if (isset($SM_TRANSLATION[$schema])) {
			return isset($SM_TRANSLATION[$schema][$phrase]) ? $SM_TRANSLATION[$schema][$phrase] : $phrase;
	#		return isset($SM_TRANSLATION[$schema][$phrase]) ? $SM_TRANSLATION[$schema][$phrase] : "";
		}
	}
}

?>