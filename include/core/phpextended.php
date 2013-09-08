<?
/**
 * phpextended
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	phpextended
 */

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.1
*/
function sm_secure_string_sql( $string ) {

	// http://www.symantec.com/connect/articles/detection-sql-injection-and-cross-site-scripting-attacks
	// Regex for detection of SQL meta-characters
	//	$string = preg_replace( "/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i", "", $string );

	// Regex for typical SQL Injection attack
	$string = preg_replace( "/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix", "", $string );

	// Regex for detecting SQL Injection with the UNION keyword
	$string = preg_replace( "/((\%27)|(\'))union/ix", "", $string );
	
//	$string = mysql_real_escape_string( $string );

	$string = addslashes( $string );

	return $string;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function sm_secure_string_xss( $string ) {

	if (is_array($string)) {
		foreach($string AS $k=>$v) {
			$string[$k] = sm_secure_string_xss($v);
		}
	}
	else {
		// http://www.symantec.com/connect/articles/detection-sql-injection-and-cross-site-scripting-attacks
		// Regex for simple CSS attack
		$string = preg_replace( "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/ix", "", $string );
		// Regex for "<img src" CSS attack
		$string = preg_replace( "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i", "", $string );
		$string = htmlentities( $string, ENT_QUOTES | ENT_HTML5, "UTF-8" );
		//$string = html_entity_decode( $string );
	}	
	return $string;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function stripslashesall($t) {
	if (is_array($t)) {
		foreach($t as $k => $v) $t[$k] = stripslashesall($v);
		return $t;
	}
	else
		return stripslashes($t);
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function addslashesall($t) {
	if (is_array($t)) {
		foreach($t as $k => $v) $t[$k] = addslashesall($v);
		return $t;
	}
	else
		return addslashes($t);
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function htmlentitiesall( $t ) {
	$replace = array( "<"=>"&lt;", "\""=>"&quot;", ">"=>"&gt;" );
	if (is_array($t)) {
		foreach($t as $k => $v) $t[$k] = htmlentitiesall($v);
		return $t;
	}
	else
		return strtr($t, $replace);
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function trimall($t) {
	if(is_array($t)) {
		foreach($t as $k => $v) $t[$k] = trimall($v);
		return $t;
	}
	else
		return trim($t);
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function rznij_tekst($text, $maxznakow, $addwielokropek = "...", $tnij_wyrazy=false ) {
	if (strlen($text)>$maxznakow) {
		$text = substr($text,0,$maxznakow);
		if (!$tnij_wyrazy) {
			if (function_exists(preg_replace)) {
				$text = preg_replace("/([\b\s\&]+\w+$)/", "", $text)." ";
			}
			else {
				$text = ereg_replace("([\b\s]+\w+)", "", $text )." ";
			}
		}
		if ($addwielokropek)
			$text .= $addwielokropek;
	}
	return $text;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function root_session_decode( $SESSION ) {
	$sess_array = Array();  
	//replace values within quotes since there might be ; or {} within the quotes
	//these would mess up the slicing in the next step
	$replaceStr = array();
	$replaceStrCount = 0; //index for the saved replacement strings
	$replaceParts = explode('"',$SESSION);
	for ($i = 1; $i < count($replaceParts); $i=$i+2) {
		$replaceStr[$replaceStrCount] = $replaceParts[$i];
		$replaceParts[$i] = "repl_" . $replaceStrCount;
		$replaceStrCount++;
	}
	$SESSION = implode('"',$replaceParts);

	$vars = array();
	$varCount = 0;
	$flag = true;

	//slice the string unsing ; and {} as separators, but keep them
	while ( $flag ) {
		if (strpos($SESSION,";") < strpos($SESSION,"{") || (strpos($SESSION,";") !== false && strpos($SESSION,"{") === false )) {
			$vars[$varCount] = substr($SESSION,0,strpos($SESSION,";")+1);
			$SESSION = substr($SESSION,strpos($SESSION,";")+1,strlen($SESSION));
		}
		else if (strpos($SESSION,";") > strpos($SESSION,"{") || (strpos($SESSION,";") === false && strpos($SESSION,"{") !== false )) {
			$vars[$varCount] = substr($SESSION,0,strpos($SESSION,"}")+1);
			$SESSION = substr($SESSION,strpos($SESSION,"}")+1,strlen($SESSION));
		}

		if (strpos($SESSION,";") === false && strpos($SESSION,"{") === false) {
			$flag = false;
		}
		else {
			$varCount++;
		}
	}
	//replace the quote substitutes with the real values 

	for ($i = 0; $i < count($vars);$i++) {
		//break apart because there might be more than one string to replace
		$varsParts = explode('"',$vars[$i]);
		for ($j = 1; $j < count($varsParts); $j++) {
			$k = count($replaceStr);
			while ($k > -1) {
				$pat = "repl_" . $k;
				if (strpos($varsParts[$j],$pat) !== false) {
					$varsParts[$j] = str_replace("repl_" . $k,$replaceStr[$k],$varsParts[$j]) ;
					break;
				}
				else {
					$k--;
				}
			}
		}
		//glue varsParts
		$vars[$i] = implode('"',$varsParts);
	}

	for ($i=0; $i < sizeof($vars); $i++)  {                
		$parts = explode("|", $vars[$i]);  
		$key = $parts[0];  
		$val = unserialize($parts[1]);  
		$sess_array[$key] = $val; 
	}
	return $sess_array;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function unlink_all( $file ) {
	$file = ereg_replace("(\/)$", "", $file);
	if (file_exists($file)) {
		chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle)) {
				if ($filename != "." && $filename != ".." && $filename) {
					unlink_all($file."/".$filename);
				}
			}
			closedir($handle);
			rmdir($file);
		} else {
			unlink($file);
		}
	}
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function root_wordwrap($string, $cols = 80, $prefix = "") {

	$t_lines = split( "\n", $string);
	$outlines = "";

	while(list(, $thisline) = each($t_lines)) {
		if(strlen($thisline) > $cols) {

			$newline = "";
			$t_l_lines = split(" ", $thisline);

			while(list(, $thisword) = each($t_l_lines)) {
				while((strlen($thisword) + strlen($prefix)) > $cols) {
					$cur_pos = 0;
					$outlines .= $prefix;

					for($num=0; $num < $cols-1; $num++) {
						$outlines .= $thisword[$num];
						$cur_pos++;
					}

					$outlines .= "\n";
					$thisword = substr($thisword, $cur_pos, (strlen($thisword)-$cur_pos));
				}

				if((strlen($newline) + strlen($thisword)) > $cols) {
					$outlines .= $prefix.$newline."\n";
					$newline = $thisword." ";
				} else {
					$newline .= $thisword." ";
				}
			}

			$outlines .= $prefix.$newline."\n";
		} else {
			$outlines .= $prefix.$thisline."\n";
		}
	}
	return $outlines;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_nip($nip) {
	// tworzenie tablicy wag
	$steps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
	// wycinanie zbędnych znaków
	$nip = preg_replace("/([^\d])/s", "", $nip);
	if (strlen($nip) != 10)
		return 0;
	// tworzenie sumy iloczynów
	for ($x = 0; $x < 9; $x++)
		$sum_nb += $steps[$x] * $nip[$x];
	$sum_m = $sum_nb % 11;
	if ($sum_m == $nip[9])
		return $nip;
	return 0;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function format_nip( $nip ) {
	if ( $nipx=preg_split("//", $nip) ) {
		return $nipx[1].$nipx[2].$nipx[3]."-".$nipx[4].$nipx[5].$nipx[6]."-".$nipx[7].$nipx[8]."-".$nipx[9].$nipx[10];
	}
	return 0;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_regon($regon) {
	// tworzenie tablicy wag
	$steps = array(8, 9, 2, 3, 4, 5, 6, 7);
	// wycinanie zbędnych znaków
	$regon = preg_replace("/([^\d])/s", "", $regon);
	if (strlen($regon) != 9)
		return 0;
	// tworzenie sumy iloczynów
	for ($x = 0; $x < 8; $x++)
		$sum_nb += $steps[$x] * $regon[$x];
	$sum_m = $sum_nb % 11;
	if($sum_m == 10)
		$sum_m = 0;
	if ($sum_m == $regon[8])
		return $regon;
	return 0;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_pesel($pesel) {
	// tworzenie tablicy wag
	$steps = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);
	// wycinanie zbędnych znaków
	$pesel = preg_replace("/([^\d])/s", "", $pesel);
	for ($x = 0; $x < 10; $x++)
		$sum_nb += $steps[$x] * $pesel[$x];
	$sum_m = 10 - $sum_nb % 10;
	if ($sum_m == 10)
		$sum_c = 0;
	else
		$sum_c = $sum_m;
	if ($sum_c == $pesel[10])
		return $pesel;
	return 0;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function uniq_dir_create( $id, $path ) {
	$size = 1;
	$val  = md5($id);
	$dir  = substr($val,0,$size);
	if (!is_dir($path)){
		mkdir($path, 0770);
		chmod($path, 0770);
	}
	if (!is_dir($path."/".$dir)) {
		mkdir($path."/".$dir, 0770);
		chmod($path."/".$dir, 0770);
	}
	return $dir;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function remove( $file ) {
	if (ereg("Windows", $_ENV["OS"]) ) {
		$file = ereg_replace("(/)", "\\", $file);
		exec("del /q ".$file);
	}
	else {
		exec("rm -f ".$file);
	}
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function root_img_resize( $img ) {
	$im = new Imagick();
	$im->readImage( $img["src"] );
	#$im->resizeImage( $img["width"], $img["height"], Imagick::FILTER_GAUSSIAN, 1, true );
	switch($img["function"]) {
		case "cropThumbnailImage":
			$im->cropThumbnailImage( $img["width"], $img["height"] );
			break;
		default:
			$im->scaleImage( $img["width"], $img["height"], $img["bestfit"] );
	}
	$im->writeImage( $img["trg"] );
	$im->clear();
	$im->destroy(); 
}

/**
 * Name: MEL :: Better Check Email Function
 *
 * Description:**UPDATED** This function will double check and validate an E-mail address by checking the sintaxis first a
 * nd the domain's MX, A and CNAME records to be valid and active. It will return TRUE if the email is valid or FALSE if not,
 * very simple. The best approach I've made to validate an Email. Let me know this has been useful, your comments and suggestions
 * are very much appreciate it. **Please Vote**
 * By: Melvin D. Nava
 *
 * Assumes:Relies on the checkdnsrr PHP
 *     Function to do the DNS work. Not available for Windows. I've included a replacement (ONLY WIN32)
 *
 * This code is copyrighted and has limited warranties.
 * Please see http://www.Planet-Source-Code.com/vb/scripts/ShowCode.asp?txtCodeId=1316&lngWId=8
 * for details.
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_email_valid($email) {
	if( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) ||
		(preg_match('/^[\d\w\-\.\_]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) {
		$host = explode('@', $email);
		if(checkdnsrr($host[1].'.', 'MX') ) return true;
		if(checkdnsrr($host[1].'.', 'A') ) return true;
		if(checkdnsrr($host[1].'.', 'CNAME') ) return true;
	}
	return false;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
if (!function_exists('checkdnsrr')) {
	function checkdnsrr($host, $type = '') {
		if(!empty($host)) {
			if($type == '')
				$type = "MX";
			@exec("nslookup -type=$type $host", $output);
			while(list($k, $line) = each($output)) {
				if(eregi("^$host", $line)) {
					return true;
				}
			}
			return false;
		}
	}
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

/**
 *	CheckPasswordStrength
 *	Checks the strength of a password and return an integer value depening on it's strength
 *
 *	Autor: john tindell (http://www.mindlessant.co.uk/)
 *
 *  1 - weak
 *  2 - not weak
 *  3 - acceptable
 *  4 - strong
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.1
*/
function CheckPasswordStrength($password) {
	$strength = 0;
	$patterns = array('#[a-z]#','#[A-Z]#','#[0-9]#','/[.!".$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/');
	foreach($patterns as $pattern) {
		if(preg_match($pattern,$password,$matches)) {
			$strength++;
		}
	}
	return $strength;

}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function uuid() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function random_pass_gen($len=8) {
	$pass = '';
	srand((float) microtime() * 10000000);
	for($i=0; $i<$len; $i++) {
		$pass .= chr(rand(97, 122));
	}
	return $pass;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function client_info() {
	// proxy
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ret["client"]["ip"]   = $_SERVER["HTTP_X_FORWARDED_FOR"];
		$ret["proxy"]["ip"]    = $_SERVER["REMOTE_ADDR"];
		$ret["client"]["name"] = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : "";
		$ret["proxy"]["name"]  = isset($_SERVER["REMOTE_NAME"]) ? $_SERVER["REMOTE_ADDR"] : @GetHostByAddr($_SERVER["REMOTE_ADDR"]);
	}
	else {
		$ret["client"]["ip"]   = $_SERVER["REMOTE_ADDR"];
		$ret["client"]["name"] = isset($_SERVER["REMOTE_NAME"]) ? $_SERVER["REMOTE_ADDR"] : @GetHostByAddr($_SERVER["REMOTE_ADDR"]);
	}

	return $ret;
}

/**
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function checkaccss_to_site_by_hostallowlist() {
	if($result=content_hostallow_fetch_active()){
		while($row=$result->fetch(PDO::FETCH_ASSOC)){
			if( checkaccess_by_hostallow( $row["content_hostallow__hosts"] )) {
				return true;
			}
		}
		die("ACCESS DENY");
	}
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function checkaccess_by_hostallow($ipallowlist) {

	$ipaddr = str_replace('::ffff:','',$_SERVER['REMOTE_ADDR']);
	$allowedlist = explode(',',$ipallowlist);

	$net = '';
	$mask = '';

	foreach($allowedlist as $value) {

		if(strpos($value, '/')===FALSE)
			$net = $value;
		else
			list($net, $mask) = explode('/', $value);

		$net = trim($net);
		$mask = trim($mask);

		if($mask == '')
			$mask = '255.255.255.255';
		elseif(is_numeric($mask))
			$mask = prefix2mask($mask);

		if(isipinstrict($ipaddr, $net, $mask)) {
			return true;
		}
	}
	return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function isipinstrict($ip,$net,$mask) {
	if(ip_long($ip) >= ip_long(getnetaddr($net,$mask)) && ip_long($ip) <= ip_long(getbraddr($net,$mask)))
		return true;
	else
		return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function prefix2mask($prefix) {
	if($prefix>=0&&$prefix<=32) {
		$out = '';
		for($ti=0;$ti<$prefix;$ti++)
			$out .= '1';
		for($ti=$prefix;$ti<32;$ti++)
			$out .= '0';
		return long2ip(bindec($out));
	}
	else
		return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function mask2prefix($mask) {
	if(check_mask($mask)) {
		return strlen(str_replace('0','',decbin(ip2long($mask))));
	}
	else {
		return -1;
	}
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function ip_long($sip) {
	if(check_ip($sip)){
		return sprintf('%u',ip2long($sip));
	}
	else {
		return 0;
	}
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_ip($ip) {
	$count = 0;
	$x = explode('.', $ip);
	$max = count($x);
	for ($i = 0; $i < $max; $i++)
		if ($x[$i] >= 0 && $x[$i] <= 255 && preg_match('/^\d{1,3}$/', $x[$i]))
			$count++;
	if ($count == 4 && $max == 4)
		return true;
	else
		return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function getnetaddr($ip,$mask) {
	if(check_ip($ip)) {
		$ipa=ip2long($ip);
		$maska=ip2long($mask);
		$ipb = decbin($ipa);
		while (strlen($ipb) < 31)
			$ipb = '0'.$ipb;
		$maskb = decbin($maska);
		while (strlen($maskb) < 31)
			$maskb = '0'.$maskb;
		$out = '00000000000000000000000000000000';
		for ($i=0; $i<32; $i++)
			if ($maskb[$i] == '1')
				$out[$i]=$ipb[$i];
		return long2ip(bindec($out));
	}
	else
		return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function getbraddr($ip,$mask) {
	if(check_ip($ip) && check_mask($mask)) {
		$ipa=ip2long($ip);
		$maska=ip2long($mask);
		$ipb = decbin($ipa);
		while (strlen($ipb) != 32) {
			$ipb = '0'.$ipb;
		}
		$maskb = decbin($maska);
		$i=0;
		$out = '';
		while (($i<32) && ($maskb[$i]=='1')) {
			$out .= $ipb[$i];
			$i++;
		}
		while(strlen($out) != 32)
			$out.='1';
		return long2ip(bindec($out));
	}
	else
		return false;
}

/**
 * based on LMS
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function check_mask($mask) {
	$i=0;
	$j=0;
	$maskb=decbin(ip2long($mask));
	if (strlen($maskb) < 32)
		return FALSE;
	else {
		while (($i<32) && ($maskb[$i] == '1')) {
			$i++;
		}
		$j=$i+1;
		while (($j<32) && ($maskb[$j] == '0')) {
			$j++;
		}
		if ($j<32)
			return FALSE;
		else
			return TRUE;
	}
}

/**
 * http://snipplr.com/view.php?codeview&id=60559
 *
 * @category	phpextended
 * @package		core
 * @version		5.0.0
*/
function pretty_json($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;
        
        // If this character is the end of an element, 
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }
            
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        $prevChar = $char;
    }

    return $result;
}
?>