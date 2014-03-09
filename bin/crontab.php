#!/usr/bin/php -q
<?

define("SESSION_DISABLED",true);
$ROOT_DIR = dirname(__FILE__)."/../";
$INCLUDE_DIR = $ROOT_DIR."/include";
require $INCLUDE_DIR."/init.php";

if($_SERVER["argv"][1]){
	if (function_exists( $_SERVER["argv"][1] )){
		eval ( "echo ".$_SERVER["argv"][1]."();" );
		exit;
	}
}

$cron = new CronParser();
if($result = content_crontab_fetch_active()){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){

echo " check: ".$row["content_crontab__name"]."\n";
		if($row["content_crontab__lastrunat"]!="0000-00-00 00:00:00" && $row["content_crontab__laststatus"] == 0){
echo " error\n";
		}
		if ($cron->calcLastRan($row["content_crontab__mhdmd"])) {
			//0=minute, 1=hour, 2=dayOfMonth, 3=month, 4=week, 5=year
			$lastRan = $cron->getLastRan();
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $row["content_crontab__lastrunat"], $tmp)) {
				list($null, $_ly, $_lm, $_ld, $_lh, $_li, $_ls) = $tmp;
				$row["content_crontab__lastrunat"] = mktime($_lh, $_li, $_ls, $_lm, $_ld, $_ly);
			}

			if ($cron->getLastRanUnix() > $row["content_crontab__lastrunat"]) {
				content_crontab_updaterunat($row["content_crontab__id"]);
				eval( "\$lastresult = ".$row["content_crontab__exec"]."(); ");
echo " run ".$row["content_crontab__exec"]."(); \n";
				content_crontab_updatestatus( $row["content_crontab__id"], $lastresult[0], $lastresult[1] );
			}
		}
	}
}

?>
