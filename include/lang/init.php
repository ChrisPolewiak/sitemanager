<?
/**
 * translation
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	translation
 */

$SM_TRANSLATION_LANGUAGES = $SITE_LANG;

/**
 * @category	translation
 * @package		core
 * @version		5.0.0
*/
function core_language_load() {
	global $SM_TRANSLATION_LANGUAGES, $SM_TRANSLATION, $INCLUDE_DIR, $SM_PLUGINS, $LANG;

	unset($LANG);
	if(isset($_REQUEST["lang"])) {
		if ( isset($SM_TRANSLATION_LANGUAGES[ $_REQUEST["lang"] ]) ) {
			$LANG = $_REQUEST["lang"];
			$_SESSION["lang"] = $_REQUEST["lang"];
		}
	}
	else {
		$LANG = isset($_SESSION["lang"]) ? $_SESSION["lang"] : "pl";
	}

	if (is_file($INCLUDE_DIR."/lang/".$LANG.".ini.php")) {
		$data = parse_ini_file($INCLUDE_DIR."/lang/".$LANG.".ini.php") or die("parse lang error");
	}
	else {
		$data = parse_ini_file($INCLUDE_DIR."/lang/pl.ini.php") or die("parse lang error");
	}
	foreach($data AS $k=>$v) {
		$k = strtoupper($k);
		if ($k!="_DEFINE_") {
			$SM_TRANSLATION["CORE"][$k] = $v;
		}
	}

	// lang from plugins
	foreach($SM_PLUGINS AS $plugin_name=>$plugin_data) {
		if (is_file($plugin_data["dir"]."/lang/".$LANG.".ini.php")) {
			$data = parse_ini_file($plugin_data["dir"]."/lang/".$LANG.".ini.php") or die("parse lang error");
		}
		else {
			$data = parse_ini_file($plugin_data["dir"]."/lang/pl.ini.php") or die("parse lang error");
		}
		foreach($data AS $k=>$v) {
			$k = strtoupper($k);
			if ($k!="_DEFINE_") {
				$SM_TRANSLATION[ $plugin_name ][$k] = $v;
			}
		}
		unset($data);
	}
	
	return $LANG;
}

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
		eval ("\$phrase = sprintf( \"".addslashes($SM_TRANSLATION[$schema][$phrase])."\", \"".join( "\",\"" ,$fnargs)."\"); ");
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