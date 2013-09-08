<?

//ob_start();

$pathinfo = pathinfo( dirname(__FILE__) );
$ROOT_DIR = $pathinfo["dirname"];
$INCLUDE_DIR = $ROOT_DIR."/include";

set_include_path(get_include_path() . PATH_SEPARATOR . $ROOT_DIR."/staff");

// Action for FORMS
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";

//
// URI request parse
//
if ( preg_match("/^\/([^\/]+)\/*([^\?]*)/", $_SERVER["REQUEST_URI"], $tmp)) {
        $url = $tmp[2];
}

if (ereg("\/", $url))
	$page = substr($url,0,strpos($url,"/"));
else
	$page = $url;

$page = $page ? $page : "index.php";

$url = str_replace("/","&",$url);
parse_str($url);

$ENGINE = "/".$SM_ADMIN_PANEL;

// require "../include/init.php";

/*
 * language
 */
require $INCLUDE_DIR."/core/staff.php";

header_gzip_start();

//
// account
//

if(is_array($_SESSION["content_user"])) {
	unset($content_usergroup);
	$_SESSION["content_usergroup"];
	// lista grup uzytkownika
	if($result = content_user2content_usergroup_fetch_by_content_user( $_SESSION["content_user"]["content_user__id"] )) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$content_usergroup[$row["content_usergroup__id"]] = 1;
		}
		$_SESSION["content_usergroup"] = $content_usergroup;
	}
	unset($content_useracl);
	$_SESSION["content_useracl"];
	// lista dostępów dla użytkownika
	if($result = content_useracl_fetch_by_user( $_SESSION["content_user"]["content_user__id"] ) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$tmp = split("\|", $row["content_access__tags"]);
			foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
		}
	}
	// lista dostępów dla grup użytkownika
	foreach($content_usergroup AS $k=>$v)
		$content_usergroup_flip[]=$k;

	if($result = content_usergroupacl_fetch_by_usergroup( $content_usergroup_flip ) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$tmp = split("\|", $row["content_access__tags"]);
			foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
		}
	}
	ksort($content_useracl);
	$_SESSION["content_useracl"] = $content_useracl;
}
elseif($page!="login.php") {
	$backto = base64_encode($REQUEST_URI);
	header("Location: /".$SM_ADMIN_PANEL."/login.php?backto=$backto");
	exit;
}

if( $_SESSION["ADMIN_SESSION"]["content_user__admin_hostallow"] && !checkaccess_by_hostallow($_SESSION["ADMIN_SESSION"]["content_user__admin_hostallow"]) ){
	$ERROR[]=__("core", "LOGIN__ERROR_ACCESS_DENIED_FROM_IP");
	exit;
}

if (isset($_REQUEST["logout"])) {
	unset($_SESSION["content_user"]);
	unset($_SESSION["content_useracl"]);
	unset($_SESSION["content_usergroup"]);
	unset($_SESSION["content_access"]);
	header("Location: /".$SM_ADMIN_PANEL."/login.php");
	exit;
}

foreach($menu AS $k=>$v){

	if (is_array($v["config"]))
		$staff_page_config = $v["config"];
	else
		unset($staff_page_config);
	// strony z pluginow
	if($page == $v["url"] && $v["level"]!=0) {
		if (is_file($v["file"])) {
			$menu_id = $k;
			$access_type_id = $v["access_type_id"];

			if (is_array($staff_page_config["default_vars"])) {
				foreach($staff_page_config["default_vars"] AS $k1=>$v1) {
					eval(" \$".$k1." = \"".$v1."\"; ");
				}
			}

			$SITE_TITLE = $v["name"];
			$admin_menu_parent = $v["level"];
			include $v["file"];
			exit;
		}
		else {
			trigger_error( __("core", "Brak dokumentu").": ".$v["file"], E_USER_ERROR);
		}
	}
	// strony z core
	elseif ( $page == $v["file"] && $v["level"]!=0){

		if (is_file($ROOT_DIR."/staff/".$v["file"])) {
			$menu_id = $k;
			$access_type_id = $v["access_type_id"];
			$admin_menu_parent = $v["level"];
			include $ROOT_DIR."/staff/".$v["file"];
			exit;
		}
		else {
			include "error.php";
			exit;
		}
	}
}
switch($page){
	case "szukaj.php": case "search.php":
		include "search.php";
		break;
	case "__contentfile_browser.php";
		include "__contentfile_browser.php";
		break;
	case "__contentfile_image_resize.php";
		include "__contentfile_image_resize.php";
		break;
	case "__contentfile_edit_file.php";
		include "__contentfile_edit_file.php";
		break;
	case "__contentfile_browser_fckeditor.php";
		include "__contentfile_browser_fckeditor.php";
		break;
	default:
		include $ROOT_DIR."/staff/index.php";
}


?>
