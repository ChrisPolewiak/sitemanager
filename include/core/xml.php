<?
/**
 * xml_tools
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	xml_tools
 */

/**
 * @category	xml_tools
 * @package		core
 * @version		5.0.0
*/
function xml_findAttribute($object, $attribute) {

	if (! is_object($object) ) {
		return 0;
	}
	if (! $object->attributes() ) {
		return 0;
	}
	foreach($object->attributes() as $a => $b) {
		if ($a == $attribute) {
			$return = $b;
		}
	}
	if($return) {
		return $return;
	}
}

?>