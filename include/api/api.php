<?php

require $INCLUDE_DIR . "/api/default.php";
require $INCLUDE_DIR . "/api/contentuser.php";

$apiMethod = "";
$apiParams = "";
$apiEncrypt = isset( $_REQUEST["encrypted"] ) ? $_REQUEST["encrypted"] : 0;

if ( preg_match( "/api\/(.+)/", $page, $tmp ) ) {
	$apiMethod = $tmp[1];
}

if ( is_array( $API[$apiMethod] ) ) {
	$_api = $API[$apiMethod];

	$RequestMethod = $_SERVER["REQUEST_METHOD"];
	$RequestMethodAllowed = array_flip( split( "\|", $_api["RequestMethodAllowed"] ) );
	if ( ! isset( $RequestMethodAllowed[$RequestMethod] ) ) {
		$response = array( "error" => "Invalid HTTP Request Method." );
	} else {
		// Parse JSON Data
		if ( isset( $_REQUEST["json"] ) ) {
			if ( $apiEncrypt ) {
				$json = base64_decode( $_REQUEST["json"] );
				$json = sm_string_decrypt( $json );
			} else {
				$json = $_REQUEST["json"];
			}
			$json = trim($json);

			$data = json_decode( $json, 1 );

			if($_REQUEST["debug"]) {
				echo "<xmp>JSON:\n";
				echo var_export( $json, true);
				echo "\n\nDATA:\n";
				echo var_export( $data, true);
				echo "</xmp>";
				exit;
			}
		}
		// Parse Data
		elseif ( isset( $_REQUEST["data"] ) ) {
			if ( $_REQUEST["encrypted"] ) {
				$data = sm_string_decrypt( $_REQUEST["data"] );
			} else {
				$data = $_REQUEST["data"];
			}
		}

		if ( $_api["acl"] && $_REQUEST[ "sessionid" ] ) {
			$tmp_session = core_session_dane( $_REQUEST[ "sessionid" ] );
			$tmp_session = json_decode( $tmp_session, 1 );
			sm_core_auth__create_sessiondata( $tmp_session["content_user"]["content_user__id"] );

			if ( ! sm_core_content_user_accesscheck( $_api["acl"], $error = false ) ) {
				$response = array( "error" => "Access Denied. Required ACL '" . $_api["acl"] . "'.", );
			} else {
				eval( " \$response = " . $_api["function"] . "(); " );
			}
		} elseif ( ! $_api["acl"] ) {
			eval( " \$response = " . $_api["function"] . "(); " );
		} else {
			$response = sm_api_auth();
		}
	}
} else {
	$response = array(
		"error" => "Invalid Method.",
		);
}

if ( $apiMethod == "documentation" ) {
	$format = isset( $_REQUEST["format"] ) ? $_REQUEST["format"] : "html";
} else {
	$format = isset( $_REQUEST["format"] ) ? $_REQUEST["format"] : "json";
}

if ( $apiEncrypt ) {
	header( "Content-Type: application/octet-stream" );
	header( "Content-Transfer-Encoding: base64" );
	$data = sm_api__result_encode( $format, $response );
	$encrypted = sm_string_encrypt( $data );
	echo base64_encode( $encrypted );
} else {
	switch ( $format ) {
		case "html":
			header( "Content-Type: text/html" );
			echo sm_api__result_encode( "html", $response );
			break;
		case "xml":
			header( "Content-Type: application/xml" );
			echo sm_api__result_encode( "xml", $response );
			break;
		default:
			header( "Content-Type: text/plain" );
			echo sm_api__result_encode( "json", $response );
	}
}

?>