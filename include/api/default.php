<?

function sm_api__query_decode($querytype, $encoded_query) {
	switch( strtolower($querytype) ) {
		case "xml":
			$xml = simplexml_load_string( $encoded_query );
			foreach($xml->query[0] AS $k=>$v) {
				$query[$k] = (string) $v;
			}
			break;
		case "get": default:
			$query = $encoded_query;
			break;
	}
	return $query;
}

#
##
#

function sm_api__result_encode($resulttype, $response) {

	switch( strtolower($resulttype) ) {
		case "xml": default:
			$dom = new DOMDocument("1.0", "utf-8");
			if(is_array($response)) {

				$xml_response = new SimpleXMLElement("<response/>");

				foreach( $response AS $k=>$v ) {
					if ( is_array($v) ) {
						$xml_item = $xml_response->addChild("item");
						foreach($v AS $k2=>$v2) {
							$xml_item->addChild( $k2, $v2 );
						}
					}
					else {
						$xml_item = $xml_response->addChild($k,$v);
					}
				}
				$response = $xml_response;
			}
//			else {
				$dom_response  = dom_import_simplexml($response);
				$dom_response = $dom->importNode($dom_response, true);
				$dom_response = $dom->appendChild($dom_response);
//			}
			$dom->formatOutput = TRUE;
			return $dom->saveXml();
			break;

		case "json": default:
			$json = json_encode($response);
			return $json;
			break;
	}
	return $result;
}

#
##
#

$API["auth"] = array(
	"name"=>"API Authenticate",
	"function"=>"sm_api_auth",
	"RequestMethodAllowed"=> "GET|POST",
	"params"=> "@login,pass",
);
function sm_api_auth() {
	global $SOFTWARE_INFORMATION;
	global $contentuser, $contentusergroup, $contentuseracl;

	$login = $_SERVER['PHP_AUTH_USER'] ? $_SERVER['PHP_AUTH_USER'] : $_REQUEST["login"];
	$pass  = $_SERVER['PHP_AUTH_PW'] ? $_SERVER['PHP_AUTH_PW'] : $_REQUEST["pass"];

	if ($login && $pass ) {
		if ( ! $tmp_content_user = content_user_get_by_username( $login ) ) {
			return array( "error"=>"Login not valid.", );
		}
		else {
			$delta = 0;
			if( $tmp_content_user["content_user__login_falsecount"]>=3 ) {
				$delta_req = 3*60;
				$delta = time() - $tmp_content_user["content_user__login_false"];
			}
			if ($delta < $delta_req) {
				return array( "error"=>"Account locked for ".($delta_req-$delta)." sec because bad password" );
			}
			else {
				$pass_crypted = crypt( $pass, $tmp_content_user["content_user__password"] );
				if ($pass_crypted != $tmp_content_user["content_user__password"] ) {
					content_user_login_status_update( $tmp_content_user["content_user__id"], false );
					return array( "error"=>"Bad password" );
				}
				else {
					if( $tmp_content_user["content_user__admin_hostallow"] && !checkaccess_by_hostallow($tmp_content_user["content_user__admin_hostallow"]) ){
						return array( "error"=>"Access from your IP is denied" );
					}
					else {
						if($tmp_content_user["content_user__status"]==3) {
							return array( "error"=>"Yout account is disabled" );
						}
						else {					
							content_user_login_status_update( $tmp_content_user["content_user__id"], true );

							sm_core_auth__create_sessiondata( $tmp_content_user["content_user__id"] );
							return array("sessionid"=>session_id());
						}
					}
				}
			}
		}
		return array(
			"error"=>"Please login first.",
		);
		unset($_SERVER['PHP_AUTH_USER']);
		unset($_SERVER['PHP_AUTH_PW']);
	}
	elseif (!$_SERVER['PHP_AUTH_USER']) { 
		$realm = $SOFTWARE_INFORMATION["application"]." ".$SOFTWARE_INFORMATION["version"]." - API";
		header("WWW-Authenticate: Basic realm=\"".$realm."\"");
		header("HTTP/1.0 401 Unauthorized");
		$response = array(
			"error"=>"Please login first.",
		);
		return $response;
	}
}

$API["documentation"] = array(
	"name"=>"API Documentation",
	"function"=>"sm_api_documentation",
	"RequestMethodAllowed"=> "GET|POST",
);
function sm_api_documentation() {
	global $API;

	foreach($API AS $api_name=>$api_data) {
		unset($api_data["function"]);
		$response[$api_name] = $api_data;
		$response[$api_name]["method"] = $api_name;
	}
	return $response;
}

?>