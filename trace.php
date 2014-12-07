<?

if(isset($_REQUEST["trace"]) && $_REQUEST["trace"]=="1234567890")
	define("SM_TRACE",1);
else
	define("SM_TRACE",0);

function sm_trace( $message ) {
	global $SM_TRACE, $fp_trace;

	$backtrace = debug_backtrace();
	$time = round(microtime(true)-$_SERVER["REQUEST_TIME_FLOAT"],4);
	$trace_file = $backtrace[0]["file"];
	$trace_line = $backtrace[0]["line"];

	if(SM_TRACE) {
		printf("%4.4f s\t%s\t%s:%s\t%s<br>\n", $time, $_SERVER["SM_NODENAME"], $trace_file, $trace_line, $message);
	}

	$SM_TRACE["last"] = $time;
	if($SM_TRACE["last"]>2)
	{
		if (! $fp_trace )
		{
			$fp_trace = fopen("/tmp/tracelog-".date("YmdHis")."--".$SM_TRACE["last"].".txt","w");
			foreach( $SM_TRACE["log"] AS $line )
				fputs($fp_trace, sprintf("%4.4f s\t%s\t%s:%s\t%s\n", $line["timestamp"], $line["server"], $line["filename"], $line["fileline"], $line["message"]));
			unset($SM_TRACE["last"]);
		}
		else
			fputs($fp_trace, sprintf("%4.4f s\t%s\t%s:%s\t%s\n", $time, $_SERVER["SM_NODENAME"], $trace_file, $trace_line, $message));
	}
	else
	{
		$SM_TRACE["log"][] = array(
			"timestamp" => $time,
			"server" => isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "",
			"filename" => $trace_file,
			"fileline" => $trace_line,
			"message" => $message,
		);
	}
}

function sm_trace_end()
{
	global $SM_TRACE;
	if($SM_TRACE["last"]>2)
	{
		$fp_trace = fopen("/tmp/tracelog-".date("YmdHis")."--".$SM_TRACE["last"].".txt","w");
		foreach( $SM_TRACE["log"] AS $line )
			fputs($fp_trace, sprintf("%4.4f s\t%s\t%s:%s\t%s\n", $line["timestamp"], $line["server"], $line["filename"], $line["fileline"], $line["message"]));
		fclose($fp_trace);
	}
}
register_shutdown_function("sm_trace", "End");

?>
