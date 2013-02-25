<?
/**
 * messages
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	messages
 */

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function cms_error( $errno, $errstr, $errfile, $errline, $errcontext ) {
	global $ERROR_TYPES;
	global $PDO_ERROR;


	if ($errno==E_NOTICE) return;
	if ($errno==E_STRICT) return;
	if ($errno==E_USER_NOTICE) return;
	if ($errno==E_WARNING) return;
	if ($errno==E_DEPRECATED) return;
	if ($errno==E_USER_DEPRECATED) return;
	$SQL_QUERY = $error ? $error : $SQL_QUERY;
	ob_clean();

#echo " $errno, $errstr, $errfile, $errline, $errcontext ";

	$backtrace = debug_backtrace();
?>	
<html>
<head>
	<title>Application Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
body {
	padding: 10px;
	margin: 0px;
	background: #eee;
	font-family: "trebuchet ms";
	font-size: 12px;
}
h1 {
	font-size: 20px;
	background: #999;
	padding: 5px;
	margin: 0;
}
.sm-error-box {
	border: 1px solid #c00;
	background: #fff;
	padding: 5px 10px;
}
h2 {
	font-size: 17px;
	padding: 2px 0px;
	margin: 0;
	border-bottom: 1px solid #999;
	color: #c00;
}
.sm-error-message {
	padding: 10px;
}
dt {
	font-weight: bold;
}
.sm-error-trace textarea {
	width: 100%;
	height: 100px;
	font-size: 11px;
}
</style>
</head>
<body>

	<h1>SiteManger Error</h1>
	<div class="sm-error-box">
<?
	if($errstr == "SQL ERROR") {
?>
		<h2>Database Error in PDO</h2>
		<div class="sm-error-message">
			<dl>
				<dt>Function name:</dt>
				<dd><?=$backtrace[3]["function"]?><dd>
			</dl>
			<dl>
				<dt>File:<dt>
				Exception:: getFile
				<dd><?=$PDO_ERROR->getFile()?> [<?=$PDO_ERROR->getLine()?>] </dd>
			</dl>
			<dl>
				<dt>From file:</dt>
				<dd><?=$backtrace[3]["file"]?> [<?=$backtrace[3]["line"]?>]</dd>
			</dl>
			<dl>
				<dt>MySQL Error Message:</dt>
				<dd><?=$PDO_ERROR->getMessage()?></dd>
			</dl>
			<dl>
				<dt>MySQL Query:</dt>
				<dd><pre><?=$backtrace[2]["args"][1]?></pre></dd>
			</dl>
		</div>
<?
	}
	else {
		unset($backtrace[0]["args"][4]);
?>
		<h2>Application Error:</h2>
		<div class="sm-error-message">
			<dl>
				<dt>Error Level:</dt>
				<dd><?=$ERROR_TYPES[$errno]["name"]?> - <?=$ERROR_TYPES[$errno]["info"]?></dd>
			</dl>
			<dl>
				<dt>Error Message:</dt>
				<dd><?=$errstr?></dd>
			</dl>
			<dl>
				<dt>File:</dt>
				<dd><?=$errfile?> [<?=$errline?>]</dd>
			</dl>
		</div>
<?
	}
?>
		<h2>Diagnostic Data</h2>
		[<span OnClick="document.getElementById('trace').style.display='block'">show</span>]<br>
		<div id="trace" class="sm-error-trace" style="display:none">
			Select all content and mail to: cms@polewiak.pl<br>
			<textarea OnClick="this.select();this.selection.createRange()">
TRACE:
<? print_r($backtrace); ?>

----------------------------------------------------------------------------------------------------

CMS:

CMS_CUSTOMERCODE = '<?=$CMS_CUSTOMERCODE?>'
CMS_SERIALNUMBER = '<?=$CMS_SERIALNUMBER?>'

----------------------------------------------------------------------------------------------------

SERVER:
<? var_dump($_SERVER); ?>

----------------------------------------------------------------------------------------------------

PLUGINS:
<? if(is_array($GLOBALS["plugin_files_version"])) { foreach($GLOBALS["plugin_files_version"] AS $plugin=>$files_version) { ?>
plugin: <?=$plugin?> 
	name: <?=$GLOBALS["PLUGIN_CONFIG"][$plugin]["name"] ?> 
	author: <?=$GLOBALS["PLUGIN_CONFIG"][$plugin]["author"] ?> 
	version: <?=$GLOBALS["PLUGIN_CONFIG"][$plugin]["version"] ?> 
	moddate: <?=$GLOBALS["PLUGIN_CONFIG"][$plugin]["moddate"] ?> 

<? }} ?>
			</textarea>
		</div>
	</div>
</body>
</html>
<?
	exit;
}
error_reporting(E_ALL ^E_NOTICE);
set_error_handler('cms_error');
//ini_set(xmlrpc_errors,'1');


/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function show_message($message, $title="komunikat") {
	$backtrace = debug_backtrace();
	$function_name = $backtrace[0]["function"];
	trigger_error("Function $function_name was deleted!", E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function form_error( $ERROR, $width="100%" ) {
	$backtrace = debug_backtrace();
	$function_name = $backtrace[0]["function"];
	trigger_error("Function $function_name was deleted!", E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function form_warning( $WARNING, $width="100%" ) {
	$backtrace = debug_backtrace();
	$function_name = $backtrace[0]["function"];
	trigger_error("Function $function_name was deleted!", E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function page_error( $ERROR ) {
	$backtrace = debug_backtrace();
	$function_name = $backtrace[0]["function"];
	trigger_error("Function $function_name was deleted!", E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function error( $text = "Brak informacji o błędzie", $title = "Błąd" ) {
	trigger_error($text, E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.0
*/
function syserr( $text ) {
	$backtrace = debug_backtrace();
	$function_name = $backtrace[0]["function"];
	trigger_error("Function $function_name was deleted!", E_USER_ERROR);
}

/**
 * @category	messages
 * @package		core
 * @version		5.0.1
*/
function sqlerr( $functioname ) {
	global $PDO_ERROR;
	$tmp = func_get_args();
	$PDO_ERROR = $tmp[2];
	trigger_error("SQL ERROR", E_USER_ERROR);
	exit;
}

$ERROR_TYPES = array(
	1 => array("name" => "E_ERROR", "info" => "Fatal run-time errors. These indicate errors that can not be recovered from, such as a memory allocation problem. Execution of the script is halted."),
	2 => array("name" => "E_WARNING", "info" => "Run-time warnings (non-fatal errors). Execution of the script is not halted."),
	4 => array("name" => "E_PARSE", "info" => "Compile-time parse errors. Parse errors should only be generated by the parser."),
	8 => array("name" => "E_NOTICE", "info" => "Run-time notices. Indicate that the script encountered something that could indicate an error, but could also happen in the normal course of running a script."),
	16 => array("name" => "E_CORE_ERROR", "info" => "Fatal errors that occur during PHP's initial startup. This is like an E_ERROR, except it is generated by the core of PHP."),
	32 => array("name" => "E_CORE_WARNING", "info" => "Warnings (non-fatal errors) that occur during PHP's initial startup. This is like an E_WARNING, except it is generated by the core of PHP."),
	64 => array("name" => "E_COMPILE_ERROR", "info" => "Fatal compile-time errors. This is like an E_ERROR, except it is generated by the Zend Scripting Engine."),
	128 => array("name" => "E_COMPILE_WARNING", "info" => "Compile-time warnings (non-fatal errors). This is like an E_WARNING, except it is generated by the Zend Scripting Engine."),
	256 => array("name" => "E_USER_ERROR", "info" => "User-generated error message. This is like an E_ERROR, except it is generated in PHP code by using the PHP trigger_error()."),
	512 => array("name" => "E_USER_WARNING", "info" => "User-generated warning message. This is like an E_WARNING, except it is generated in PHP code by using the PHP function trigger_error()."),
	1024 => array("name" => "E_USER_NOTICE", "info" => "User-generated notice message. This is like an E_NOTICE, except it is generated in PHP code by using the PHP function trigger_error()."),
	6143 => array("name" => "E_ALL", "info" => "Enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code."),
	2048 => array("name" => "E_STRICT", "info" => "Catchable fatal error. It indicates that a probably dangerous error occured, but did not leave the Engine in an unstable state. If the error is not caught by a user defined handle (see also set_error_handler()), the application aborts as it was an E_ERROR."),
	4096 => array("name" => "E_RECOVERABLE_ERROR", "info" => "Run-time notices. Enable this to receive warnings about code that will not work in future versions. "),
	8192 => array("name" => "E_DEPRECATED", "info" => "User-generated warning message. This is like an E_DEPRECATED, except it is generated in PHP code by using the PHP function trigger_error()."),
	16384 => array("name" => "E_USER_DEPRECATED", "info" => "All errors and warnings, as supported, except of level E_STRICT in PHP < 6."),
);

?>