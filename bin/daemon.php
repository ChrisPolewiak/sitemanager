#!/usr/bin/php -q
<?

define("SESSION_DISABLED",true);
$ROOT_DIR = dirname(__FILE__)."/../";
$INCLUDE_DIR = $ROOT_DIR."/include";
require $ROOT_DIR."/trace.php";
require $INCLUDE_DIR."/init.php";
require_once "System/Daemon.php";

declare(ticks = 1);

function sig_handler($signo) {
	System_Daemon::info("sig_handler $signo");
	switch($signo) {
		case SIGINT: case SIGTERM: case SIGHUP:
			System_Daemon::info("kill main process");
			foreach( $pcntl_children AS $pid )
			{
				System_Daemon::info("kill child ($pid) process");
				posix_kill( $pid, $signo );
			}
			exit;
			break;
	}
}
pcntl_signal(SIGINT, "sig_handler");
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

$runmode = array(
	"no-daemon" => false,
	"help" => false,
	"write-initd" => false,
);

foreach ($argv as $k=>$arg) {
	if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
		$runmode[substr($arg, 2)] = true;
	}
}

if ($runmode["help"]) {
	echo "Usage: ".$argv[0]." [runmode]\n";
	echo "Available runmodes:\n";
	foreach ($runmode as $runmod=>$val) {
		echo " --".$runmod."\n";
	}
	exit;
}

$options = array(
	"appName" => "sitemanager",
	"appDir" => dirname(__FILE__),
	"appDescription" => "",
	"authorName" => "",
	"authorEmail" => "",
	"sysMaxExecutionTime" => 0,
	"sysMaxInputTime" => 0,
	"sysMemoryLimit" => "1024M",
//	"appRunAsGID" => 33,
//	"appRunAsUID" => 33,
);

System_Daemon::setOptions($options);

if (!$runmode["no-daemon"]) {
	System_Daemon::start();
}

if (!$runmode["write-initd"]) {
	System_Daemon::info("not writing an init.d script this time");
}
else {
	if (($initd_location = System_Daemon::writeAutoRun()) === false) {
		System_Daemon::notice("unable to write init.d script");
	}
	else {
		System_Daemon::info("sucessfully written startup script: %s", $initd_location);
	}
}


// CODE

$runningOkay = true;

$mode = (System_Daemon::isInBackground() ? "" : "non-" ). "daemon mode";
System_Daemon::info("{appName} xrunning in %s", $mode, $cnt);

$maxfork = 2;
for( $i=1; $i<=$maxfork; $i++)
{
	$pid = pcntl_fork();
	sleep(2);
	if( $pid == -1 )
	{
		System_Daemon::notice("Start fork Failed");
	}
	if( $pid )
	{
		$curpid = posix_getpid();
		System_Daemon::info("Start fork (pid: $pid) from (pid: $curpid)");
	}
	else
	{
		System_Daemon::info("Parent proces ($pid) start");
	}
}

while (!System_Daemon::isDying() && $runningOkay) {

	try { $SM_PDO = new PDO($DB_ENGINE .":dbname=". $DB_NAME .";host=". $DB_SERVER, $DB_USER, $DB_PASS); }
	catch(PDOException $e) { error("Connection failed: " . $e->getMessage() ); }
	$SM_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$SM_PDO->query("SET NAMES 'utf8'");

	$runningOkay = true;

	if (!$runningOkay) {
		System_Daemon::err("script produced an error");
	}

	usleep( ((int) rand(1,5))*100 );

	$start_time = microtime(true);
	$subtime1_start = round(microtime(true)-$_SERVER["REQUEST_TIME_FLOAT"],4);

#	sm_sql_transaction_begin();
	if($result = core_task_fetch_waiting( $limit=1 ) ) {
		$subtime1 = round(microtime(true)-$_SERVER["REQUEST_TIME_FLOAT"],4) - $subtime1_start;
		$row=$result->fetch(PDO::FETCH_ASSOC);
		core_task_lock( $row["core_task__id"] );
#		sm_sql_transaction_commit();

		$_params = json_decode($row["core_task__params"], true);
		$str  = " \$return = ".$row["core_task__function"]."(";
		$str2="";
		foreach($_params AS $k=>$v){
			$str2 .= $str2 ? "," : "";
			if( is_array($v) || is_object($v) )
				$v = addslashes(json_encode($v));
			$str2 .= " \$$k=\"$v\"";
		}
		$str .= $str2;
		$str .= " );";
		eval ( $str );

		$core_task__execution_time = microtime(true) - $start_time;
		System_Daemon::info("PID:$pid, Execute: $str ($return), takes: ".number_format($core_task__execution_time,2,".","")." s. (".number_format($subtime1,2,".","").")");
		core_task_result( $row["core_task__id"], $return, $core_task__execution_time );
	}

	System_Daemon::iterate(2);
}

System_Daemon::stop();

?>