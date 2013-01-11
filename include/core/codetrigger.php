<?
/**
 * codetrigger
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	codetrigger
 */

/**
 * @category	codetrigger
 * @package		core
 * @version		5.0.0
*/
function sitemanager_codetrigger_exec( $codetrigger_name, $variables ) {
	global $SM_FRAMEWORK;

	if(is_array($SM_FRAMEWORK[ $codetrigger_name ])) {
		foreach($SM_FRAMEWORK[ $codetrigger_name ] AS $codetrigger) {
			$func  = $codetrigger["function"];
			$func .= "(";
			if(is_array($codetrigger["params"])){
				$comma="";
				foreach($codetrigger["params"] AS $k=>$v) {
					if(isset($variables[$v])) {
						$comma = $comma ? ", " : " ";
						$func .= $comma;
						$func .= "\$$k='".$variables[$v]."'";
					}
				}
			}
			$func .= " );";
			eval($func);
		}
	}
}

?>