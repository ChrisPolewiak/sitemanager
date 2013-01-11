<?
/**
 * conversions
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	conversions
 */

/**
 * @category	conversions
 * @package		core
 * @version		5.0.0
*/
function conv_spec_chars( $t ) {
	$t = win2iso($t);
	$replace = array( chr(38)."#8222;"=>"\"", chr(38)."#8221;"=>"\"", chr(38)."#61623;"=>"<li>", "\t"=>" " );
	if(is_array($t)) {
		foreach($t as $k => $v) $t[$k] = conv_spec_chars($v);
		return $t;
	}
	else
		return strtr($t, $replace);
}

/**
 * @category	conversions
 * @package		core
 * @version		5.0.0
*/
function correct_chars_array( $arr ) {
	if (is_array($arr)) {
		foreach($arr AS $k=>$v) {
			$arr[$k] = correct_chars($v);
		}
	}
	else {
		$arr = correct_chars($arr);
	}
	return $arr;
}

/**
 * @category	conversions
 * @package		core
 * @version		5.0.0
*/
function iso2none( $str ) {
	$conversion = array(
	        177 => ord("a"),
	        161 => ord("A"),
	        230 => ord("c"),
	        198 => ord("C"),
	        234 => ord("e"),
	        202 => ord("E"),
	        179 => ord("l"),
	        163 => ord("L"),
	        241 => ord("n"),
	        209 => ord("N"),
	        243 => ord("o"),
	        211 => ord("O"),
	        182 => ord("s"),
	        166 => ord("S"),
	        191 => ord("z"),
	        175 => ord("Z"),
	        188 => ord("x"),
	        172 => ord("X"),
	);
	if(is_array($str)){
		foreach($str AS $k=>$v){
			$str[$k] = iso2none($v);
		}
	}
	else {
		foreach($conversion AS $from=>$to) {
			$str = preg_replace("/(".chr($from).")/s", chr($to), $str);
		}
	}
	return $str;
}

/*
 * http://pl.wikibooks.org/wiki/PHP/Internacjonalizacja
 *
 * @category	conversions
 * @package		core
 * @version		5.0.0
*/
function localStrftime($format, $timestamp = 0) {
	if($timestamp == 0) {
		// Sytuacja, gdy czas nie jest podany - u¿ywamy aktualnego.
		$timestamp = time();
	}

	// Nowy kod - %F dla odmienionej nazwy miesi¹ca
	if(strpos($format, '%F') !== false) {
		$mies = date('m', $timestamp);

		// odmienianie
		switch($mies) {
			case 1:
				$mies = 'stycznia';
				break;
			case 2:
				$mies = 'lutego';
				break;
			case 3:
				$mies = 'marca';
				break;
			case 4:
				$mies = 'kwietnia';
				break;
			case 5:
				$mies = 'maja';
				break;
			case 6:
				$mies = 'czerwca';
				break;
			case 7:
				$mies = 'lipca';
				break;
			case 8:
				$mies = 'sierpnia';
				break;
			case 9:
				$mies = 'wrzeœnia';
				break;
			case 10:
				$mies = 'paŸdziernika';
				break;
			case 11:
				$mies = 'listopada';
				break;
			case 12:
				$mies = 'grudnia';
				break;			
		}
		// dodawanie formatowania
		return strftime(str_replace('%F', $mies, $format), $timestamp);		
	}
	return strftime($format, $timestamp);	
}

?>