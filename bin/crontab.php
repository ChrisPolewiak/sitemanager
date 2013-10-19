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
if($result = contentcrontab_fetch_active()){
	while($row=$result->fetch(PDO::FETCH_ASSOC)){

echo " check: ".$row["contentcrontab_name"]."\n";
		if($row["contentcrontab_lastrunat"]!="0000-00-00 00:00:00" && $row["contentcrontab_laststatus"] == 0){
echo " error\n";
		}
		if ($cron->calcLastRan($row["contentcrontab_mhdmd"])) {
			//0=minute, 1=hour, 2=dayOfMonth, 3=month, 4=week, 5=year
			$lastRan = $cron->getLastRan();
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $row["contentcrontab_lastrunat"], $tmp)) {
				list($null, $_ly, $_lm, $_ld, $_lh, $_li, $_ls) = $tmp;
				$row["contentcrontab_lastrunat"] = mktime($_lh, $_li, $_ls, $_lm, $_ld, $_ly);
			}

			if ($cron->getLastRanUnix() > $row["contentcrontab_lastrunat"]) {
				contentcrontab_updaterunat($row["id_contentcrontab"]);
				eval( "\$lastresult = ".$row["contentcrontab_exec"]."(); ");
echo " run ".$row["contentcrontab_exec"]."(); \n";
				contentcrontab_updatestatus( $row["id_contentcrontab"], $lastresult[0], $lastresult[1] );
			}
		}
	}
}

?>
