<?
/**
 * bitlogical
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	bitlogical
 */

/**
 * @category	bitlogical
 * @package		core
 * @version		5.0.0
*/
function rootbit_init( $lenght ){
	return string_repeat("0",$lenght);
}

/**
 * @category	bitlogical
 * @package		core
 * @version		5.0.0
*/
function rootbit_add( $var, $bit ){
	if (!$var) { rootbit_init( $bit ); }
	return substr($var, 0, $bit-1)."1".substr($var, $bit+1);
}

/**
 * @category	bitlogical
 * @package		core
 * @version		5.0.0
*/
function rootbit_remove( $var, $bit ){
	if (!$var) { rootbit_init( $bit ); }
	return substr($var, 0, $bit-1)."0".substr($var, $bit+1);
}

/**
 * @category	bitlogical
 * @package		core
 * @version		5.0.0
*/
function rootbit_check( $var, $bit ){
	if ($var) {
		$val = substr($var, $bit-1, 1);
		return $val;
	}
	return 0;
}

?>