<?

require "trace.php";

if(!isset($_SERVER["PATH_INFO"])){
	$_SERVER["PATH_INFO"] = "/index";
}
ob_start();

$ROOT_DIR = dirname(__FILE__);
$INCLUDE_DIR = $ROOT_DIR."/include";

//
// URI request parse
//
$urlarr = parse_url( $_SERVER["REQUEST_URI"] );
$url = preg_replace("/^(\/)/", "", $urlarr["path"]);
$page = preg_replace("/(\/)$/", "", $url);
$page = $page ? $page : "index";

/*
 * API
 */
if($page == "api" || preg_match("/^api\//", $page)) {
	require $INCLUDE_DIR."/init.php";
	require $INCLUDE_DIR."/api/api.php";
	exit;
}

/*
 * CacheImg
 */
if($page == "cacheimg" || preg_match("/^cacheimg\//", $page)) {
	require $INCLUDE_DIR."/init.php";
	require $INCLUDE_DIR."/core/cacheimg_execute.php";
	exit;
}

/*
 * Captcha
 */
if($page == "captcha" || preg_match("/^captcha\//", $page)) {
	require $INCLUDE_DIR."/init.php";
	require $INCLUDE_DIR."/core/captcha.php";
	exit;
}

sm_trace( "Before include/init.php" );

require_once "include/init.php";

sm_trace( "After include/init.php" );

/*
 * Admin
 */
if($page == $SM_ADMIN_PANEL || preg_match("/^".$SM_ADMIN_PANEL."\//", $page)) {
	require "staff/engine.php";
	exit;
}

/*
 * CLEAN VARIABLES
 */

/*
foreach($_REQUEST AS $k=>$v) {
	$_REQUEST[$k] = sm_secure_string_xss( $v );
}
foreach($_POST AS $k=>$v) {
	$_POST[$k] = sm_secure_string_xss( $v );
}
foreach($_GET AS $k=>$v) {
	$_GET[$k] = sm_secure_string_xss( $v );
}
*/

$SM_LANG = $LANG;

checkaccss_to_site_by_hostallowlist();
sm_trace( "checkaccss_to_site_by_hostallowlist" );

sm_content_user_access_prepare();
sm_trace( "sm_content_user_access_prepare" );

//
// AVAILABLE PAGES
//

if($result=content_page_fetch_by_lang( $SM_LANG )){
sm_trace( "content_page_fetch_by_lang" );
	while($row=$result->fetch(PDO::FETCH_ASSOC)){
		if( $row["content_page__url"] ) {
			$PAGES_ALLOW[$row["content_page__url"]] = $row;
			if($row["content_page__params"]) {
				$_params = explode("|",$row["content_page__params"]);
				foreach($_params AS $_param) {
					list($k,$v) = explode("=", $_param);
					$PAGES_ALLOW[$row["content_page__url"]]["params"][$k] = $v;
				}
			}
		}
	}
}

// jesli podany adres URL jest w bazie
// sitemap.xml
if ($page=="sitemap.xml"){
	header("Content-type: application/xml");
	echo sm_core_sitemap();
	exit;
}
elseif ($PAGES_ALLOW[$page]) {

	// wymagany dostep dla osob zalogowanych
	if($PAGES_ALLOW[$page]["contentpage_requiredaccess"]) {
		if( ! $_SESSION["contentaccess"][ $PAGES_ALLOW[$page]["contentpage_requiredaccess"] ]) {
			if(is_array($_SESSION["contentuser"])) {
				$msgstr  = "<b>Nie masz obecnie dostępu do tej części serwisu</b><br>";
				$msgstr .= "<br>";
				if($_contentaccess = contentaccess_get_by_sysname( $PAGES_ALLOW[$page]["contentpage_requiredaccess"] )) {
					$msgstr .= nl2br($_contentaccess["contentaccess_message"]);
				}
				$smarty->assign("message", $msgstr);
				$smarty->display( $PAGES_ALLOW["core-access-deny"]["content_template__srcfile"] );
			}
			else {
				$backto = base64_encode($REQUEST_URI);
				header("Location: ". cms_core_geturl_by_name("account_login"). "?backto=$backto");
			}
			exit;
		}
	}

	if( $PAGES_ALLOW[$page]["contentpage_hostallow"] && !checkaccess_by_hostallow($PAGES_ALLOW[$page]["contentpage_hostallow"]) ){
		die("ACCESS DENY");
	}

	if (isset($PAGES_ALLOW[$page]["params"]["redirect_page"])) {
		header("Location: ".$ENGINE."/".$PAGES_ALLOW[$page]["params"]["redirect_page"]);
		exit;
	}

	if (isset($PAGES_ALLOW[$page]["params"]["redirect_url"])) {
		header("Location: ".$PAGES_ALLOW[$page]["params"]["redirect_url"]);
		exit;
	}

	$content_template__srcfile = "";
	if ( is_file( $ROOT_DIR."/html/pages/".$SM_LANG."/".$PAGES_ALLOW[$page]["content_template__srcfile"] )) {
		$content_template__srcfile = $ROOT_DIR."/html/pages/".$SM_LANG."/".$PAGES_ALLOW[$page]["content_template__srcfile"];
	}
	else {
		$content_template__srcfile = $ROOT_DIR."/html/pages/".$PAGES_ALLOW[$page]["content_template__srcfile"];
	}	

	if ( $content_template__srcfile ) {
		$part = pathinfo($PAGES_ALLOW[$page]["content_template__srcfile"]);
		switch($part["extension"]) {
			case "tpl":

				$smarty = new Smarty();
				$smarty->assign("CONTENT_PAGE__ID", $PAGES_ALLOW[$page]["content_page__id"] );
				$smarty->assign("SITE_TITLE", $SITE_TITLE );
				$smarty->assign("SITE_DESCRIPTION", $PAGES_ALLOW[$page]["content_page__description"] ? $PAGES_ALLOW[$page]["content_page__description"] : $SITE_DESCRIPTION );
				$smarty->assign("SITE_KEYWORDS", $PAGES_ALLOW[$page]["content_page__keywords"] ? $PAGES_ALLOW[$page]["content_page__keywords"] : $SITE_KEYWORD );
				$smarty->assign("CONTENT_PAGE", $PAGES_ALLOW[$page] );
				$smarty->assign("MONTH_NAMES", $MONTH_NAMES);
				$smarty->assign("MONTH_NAMES2", $MONTH_NAMES2);
				$smarty->assign("DAY_NAMES", $DAY_NAMES);
				$smarty->display( $PAGES_ALLOW[$page]["content_template__srcfile"] );
				break;
			case "php":
				$TEMPLATE_PATH = $ROOT_DIR."/html/pages/".$SM_LANG;
				$CONTENT_PAGE__ID = $PAGES_ALLOW[$page]["content_page__id"];
				$content_page__params = $PAGES_ALLOW[$page]["params"];
				$SITE_DESCRIPTION = $PAGES_ALLOW[$page]["content_page__description"] ? $PAGES_ALLOW[$page]["content_page__description"] : $SITE_DESCRIPTION;
				$SITE_KEYWORDS = $PAGES_ALLOW[$page]["content_page__keywords"] ? $PAGES_ALLOW[$page]["content_page__keywords"] : $SITE_KEYWORD;
				if(is_file($content_template__srcfile)) {
					require $content_template__srcfile;
				}
				else {
					header("HTTP/1.0 503 Service unavailable");
					echo "Internal Error - Requested Resource not found";
					exit;
				}
				break;
		}
	}
	else {
		if (is_file($ROOT_DIR."/html/pages/".$SM_LANG."/error_503.tpl")) {
			include $ROOT_DIR."/html/pages/".$SM_LANG."/error_503.tpl";
		}
		else {
			header("HTTP/1.0 503 Service unavailable");
			echo "Service unavailable";
			exit;
		}
	}
}
else {
	if (is_file($ROOT_DIR."/html/pages/".$SM_LANG."/error_404.html")) {
		include $ROOT_DIR."/html/pages/".$SM_LANG."/error_404.html";
	}
	else {
		header("HTTP/1.0 404 Not Found");
		echo "Document Not Found";
		exit;
	}
}

setlocale(LC_ALL, '');
?>
