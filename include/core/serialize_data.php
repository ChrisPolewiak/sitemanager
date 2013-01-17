<?
/**
 * serialize
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	serialize
 */

/**
 * @category	serialize
 * @package		core
 * @version		5.0.0
*/
function sm_core_serialize_data( $array_data, $name ) {
	global $CACHE_DIR;

	$cache_file = $CACHE_DIR."/cache-".$name.".json";
	$fp = fopen($cache_file.".tmp","w") or trigger_error(__("core", "Nie mogę otworzyć").": ".$cache_file.".tmp", E_USER_ERROR);
	fputs($fp, json_encode($array_data));
	fclose($fp);
	if (file_exists($cache_file)){
		unlink($cache_file);
	}
	rename($cache_file.".tmp", $cache_file); 
	return 1;
}

/**
 * @category	serialize
 * @package		core
 * @version		5.0.0
*/
function sm_core_unserialize_data( $name ) {
	global $CACHE_DIR;

	if(is_file($CACHE_DIR."/cache-".$name.".json")) {
		$file = file($CACHE_DIR."/cache-".$name.".json");
		return json_decode($file[0], $assoc=true);
	}
}

?>