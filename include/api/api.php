<?

require $INCLUDE_DIR."/api/default.php";
require $INCLUDE_DIR."/api/contentuser.php";

$apiMethod = "";
$apiParams = "";
if (preg_match("/api\/(.+)/", $page, $tmp)) {
	$tmp = split("\/", $tmp[1]);

	if ( is_array( $API[ $tmp[0] ] ) ) {
		$apiMethod = $tmp[0];
		unset($tmp[0]);
	}
	elseif ( is_array( $API[ $tmp[0]."/".$tmp[1] ] ) ) {
		$apiMethod = $tmp[0]."/".$tmp[1];
		unset($tmp[0]);
		unset($tmp[1]);
	}
	elseif ( is_array( $API[ $tmp[0]."/".$tmp[1]."/".$tmp[2] ] ) ) {
		$apiMethod = $tmp[0]."/".$tmp[1]."/".$tmp[2];
		unset($tmp[0]);
		unset($tmp[1]);
		unset($tmp[2]);
	}
	$apiParams = join("/", $tmp);
}

if(is_array( $API[$apiMethod] ) ) {
	$_api = $API[$apiMethod];

	$RequestMethod = $_SERVER["REQUEST_METHOD"];
	$RequestMethodAllowed = array_flip(split("\|",$_api["RequestMethodAllowed"]));
	if( ! isset( $RequestMethodAllowed[$RequestMethod] )) {
		$response = array( "error"=>"Invalid HTTP Request Method." );
	}
	else {
		if( $_api["acl"] && $_REQUEST[ "sessionid" ] ) {
			$SESSION = sql_session_dane( $_REQUEST[ "sessionid" ] );
			sm_core_auth__create_sessiondata( $_SESSION["content_user"]["content_user__id"] );

			if ( ! sm_core_content_user_accesscheck( $_api["acl"], $error=false ) ) {
				$response = array( "error"=>"Access Denied. Required ACL '".$_api["acl"]."'.", );
			}
			else {
				eval(" \$response = ".$_api["function"]."( \$apiParams ); ");
			}
		}
		elseif ( ! $_api["acl"] ) {
			eval(" \$response = ".$_api["function"]."( \$apiParams ); ");
		}
		else {
			$response = sm_api_auth();
		}
	}
}
else {
	$response = array(
		"error"=>"Invalid Method.",
	);
}

switch( $_REQUEST["format"] ) {
	case "xml":
		header("Content-Type: application/xml");
		echo sm_api__result_encode( "xml", $response );
		break;
	default:
		echo sm_api__result_encode( "json", $response );
}

?>