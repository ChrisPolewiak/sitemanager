<?

//foreach($_POST AS $k=>$v){ $$k = $_POST[$k]; }
//foreach($_GET AS $k=>$v){ $$k = $_GET[$k]; }
// Config
$SM_CONFIG = parse_ini_file ($ROOT_DIR."/config.ini.php", $process_sections=true );
if(is_array($SM_CONFIG)) {

	if ($SM_CONFIG["database"]["engine"]) {
		$DB_ENGINE = $SM_CONFIG["database"]["engine"];
		$DB_SERVER = $SM_CONFIG["database"]["dbserver"];
		$DB_NAME   = $SM_CONFIG["database"]["dbname"];
		$DB_USER   = $SM_CONFIG["database"]["dbuser"];
		$DB_PASS   = $SM_CONFIG["database"]["dbpass"];
		$DB_TABLEPREFIX = $SM_CONFIG["database"]["dbprefix"];
		define("DB_TABLEPREFIX", $SM_CONFIG["database"]["dbprefix"]);
	}

	$SITE_LANG=array();
	if (isset($SM_CONFIG["language"]["lang"])) {
		foreach($SM_CONFIG["language"]["lang"] AS $k=>$v){
			$SITE_LANG[$k]=$v;
		}
	}
	else {
		$SITE_LANG["pl"]="Polski";
	}

	define("TEST_MODE", false);
	if ($SM_CONFIG["engine"]["test_mode"]) {
		define("TEST_MODE", true);
	}

	if ($SM_CONFIG["engine"]["site_adminpanel"]) {
		$SM_ADMIN_PANEL = $SM_CONFIG["engine"]["site_adminpanel"];
	}

	$CACHE_IMAGE_TIMEOUT=86400;
	if ($SM_CONFIG["engine"]["cache_image_timeout"]) {
		$CACHE_IMAGE_TIMEOUT = $SM_CONFIG["engine"]["cache_image_timeout"];
	}
	define("CACHE_IMAGE_TIMEOUT", $CACHE_IMAGE_TIMEOUT);

	define("SM_DATA_ENCRYPTION_KEY", $SM_CONFIG["engine"]["data_encryption_key"]);
	define("SM_DATA_ENCRYPTION_CIPHER", MCRYPT_RIJNDAEL_256);

	if (isset($SM_CONFIG["mail"])) {
		$SM_MAIL_ADDR_ADMIN	= $SM_CONFIG["mail"]["mail_addr_admin"];
		$SM_MAIL_BACKEND	= $SM_CONFIG["mail"]["mail_backend"];
		$SM_SMTP_HOST		= $SM_CONFIG["mail"]["smtp_host"];
	}

	if ($SM_CONFIG["site"]) {
		$SERVER_NAME		= $SM_CONFIG["site"]["server_name"];
		$SITE_TITLE			= $SM_CONFIG["site"]["site_title"];
		$SITE_DESCRIPTION	= $SM_CONFIG["site"]["site_description"];
		$SITE_KEYWORDS		= $SM_CONFIG["site"]["site_keywords"];
	}

	if ($SM_CONFIG["support"]) {
		$CMS_CUSTOMERCODE = $SM_CONFIG["support"]["customercode"];
		$CMS_SERIALNUMBER = $SM_CONFIG["support"]["serialnumber"];
	}

}
else {
	header("Location: /install");
}

$BACKUP_DIR="";

$SOFTWARE_INFORMATION = array(
	"version"     => "5.09",
	"author"      => "Chris Polewiak",
	"application" => "SiteManager Engine",
	"date"        => "2014-03-09",
);

$BACKUP_DIR = $BACKUP_DIR ? $BACKUP_DIR : $ROOT_DIR."/backup";

// Smarty
define("SMARTY_DIR", $INCLUDE_DIR."/smarty/");
define("SMARTY_SITEMANAGER_DIR", $ROOT_DIR."/include/smarty");
define("SMARTY_TEMPLATES", $ROOT_DIR."/html/pages");

$IMG_PATH = "/img";

// init VARS
$CONTENTFILESSHOWTYPE_AVAILABLEOBJECT = array();

$CACHE_DIR = $ROOT_DIR."/cache";

require $INCLUDE_DIR."/lang/init.php";
require $INCLUDE_DIR."/core/init.php";
require $INCLUDE_DIR."/vars/init.php";

/*
 * SQL CONNECT
 */
try { $SM_PDO = new PDO($DB_ENGINE .":dbname=". $DB_NAME .";host=". $DB_SERVER, $DB_USER, $DB_PASS); } catch(PDOException $e) { error("Connection failed: " . $e->getMessage() ); }
// PDO Error handling
$SM_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$SM_PDO->query("SET NAMES 'utf8'");

require $INCLUDE_DIR."/sql/init.php";
require $ROOT_DIR."/plugin/plugin.php";

// musi byc po sql'ach
#require $INCLUDE_DIR."/addons/download.php";
#require $INCLUDE_DIR."/addons/generator.php";

// SESSION
require $INCLUDE_DIR."/core/session.php";

$LANG = core_language_load();

?>
