<?

function sm_api__result_encode($resulttype, $response) {

	if (!$response) {
		$response="null";
	}

	switch( strtolower($resulttype) ) {
		case "xml": default:
			$dom = new DOMDocument("1.0", "utf-8");
			$xml_response = new SimpleXMLElement("<response/>");
			if(is_array($response)) {
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
			}
			$response = $xml_response;
			$dom_response  = dom_import_simplexml($response);
			$dom_response = $dom->importNode($dom_response, true);
			$dom_response = $dom->appendChild($dom_response);
			$dom->formatOutput = TRUE;
			return $dom->saveXml();
			break;

		case "json": default:
			$json = json_encode($response);
			return pretty_json ($json);
			break;

		case "html": default:
			sm_api_documentation_html();
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
	"plugin" => "core",
	"group" => "system",
"encryption" => false,
"description" => "Authenticate to API",
"arguments" => array(
	array( "argument" => "login", "type" => "string", "valid" => "valid username", "default" => "required", "detail" => "username for API account", ),
	array( "argument" => "pass", "type" => "string", "valid" => "valid password", "default" => "required", "detail" => "password for API account", ),
	),
"return" => array(
	array( "path" => "/", "name" => "sessionid", "type" => "string", "description" => "session ID. Must be used in all methods, which require authentication." ),
	array( "path" => "/", "name" => "error", "type" => "string", "description" => "Error message if authenticate failed" ),
),
"example_query" => "?login=api&pass=Api123.",
"example_response" => array(
	"sessionid" => "12342b3c6f067fb6",
	"error" => "null",
	),
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
	"plugin" => "core",
	"group" => "system",
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

#
##
#

function sm_api_documentation_html() {
	global $API;

	foreach($API AS $k=>$v){
		$APIDOC[ $v["plugin"] ][ $v["group"] ][$k] = $v;
	}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="/admin/css/normalize.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/admin/css/sitemanager.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/admin/css/bootstrap.min.css" type="text/css" media="screen">
</head>
<body>
	<br>
	<div class="row-float">
		<div class="span4">

			<ul class="nav nav-list affix">
<?
	foreach($APIDOC AS $plugin=>$plugin_data) {
?>
				<li class="nav-header"><a href="#plugin_<?=$plugin?>"><?=$plugin?></a></li>
<?
		foreach($plugin_data AS $group=>$group_data) {
?>
				<li><a href="#group_<?=$group?>"><?=$group?></a></li>
<?
		}
	}
?>
			</ul>
		</div>

		<div class="span8">
<?
	foreach($APIDOC AS $plugin=>$plugin_data) {
/*
?>
			<a name="plugin_<?=$plugin?>"></a>
			<h1>Plugin: <?=$plugin?></h1>
<?
*/
	foreach($plugin_data AS $group=>$group_data) {
?>
			<section id="group_<?=$group?>">
				<div class="page-header">
					<h2><?=$group?></h2>
<?
			foreach($group_data AS $method=>$method_data) {
?>
					<h3>Method: <?=$method?></h3>
					<p><?=$method_data["description"]?></p>

<? // Authentication ?>
					<h4>Authentication</h4>
<? 				if($method_data["acl"]) { ?>
					<p>This method <b>requires</b> authentication.</p>
<?				} else { ?>
					<p>This method <b>does not require</b> authentication.</p>
<?				} ?>

<? // HTTP method ?>
					<h4>HTTP method</h4>
					<p>This method is called with HTTP method: <b><?=str_replace("|","</b>, <b>", $method_data["RequestMethodAllowed"])?></b>.</p>

<? // Arguments ?>
<?				if($method_data["arguments"]) { ?>
					<h4>Arguments</h4>
					<table class="table table-striped table-hover table-condensed table-bordered">
						<thead>
							<tr>
								<th class="table_arguments_argument">Argument</th>
								<th class="table_arguments_type">Type</th>
								<th class="table_arguments_valid">Valid Values</th>
								<th class="table_arguments_default">Default Value</th>
								<th class="table_arguments_detail">Detail</th>
							</tr>
						</thead>
						<tbody>
<?
						foreach($method_data["arguments"] AS $argument) {
?>
							<tr>
								<td><?=$argument["argument"]?></td>
								<td><?=$argument["type"]?></td>
								<td><?=$argument["valid"]?></td>
								<td><?=$argument["default"]?></td>
								<td><?=$argument["detail"]?></td>
							</tr>
<?
						}
?>
					</table>
<?					} ?>

<? // Returned Values ?>
<?				if($method_data["return"]) { ?>
					<h4>Returned Values</h4>
					<table class="table table-striped table-hover table-condensed table-bordered">
						<thead>
							<tr>
								<th class="table_return_path">Element (path)</th>
								<th class="table_return_name">Name</th>
								<th class="table_return_type">Type</th>
								<th class="table_return_description">Description</th>
							</tr>
						</thead>
						<tbody>
<?
					foreach($method_data["return"] AS $return) {
?>
							<tr>
								<td><?=$return["path"]?></td>
								<td><?=$return["name"]?></td>
								<td><?=$return["type"]?></td>
								<td><?=$return["description"]?></td>
							</tr>
<?
					}
?>
					</table>
<?					} ?>

<?/*
<? // Example Query ?>
					<h4>Example Query</h4>
					<pre><?=$_SERVER["HTTPS"]=="on"?"https://":"http://"?><?=$_SERVER["SERVER_NAME"]?>/api/<?=$method?>/<b><?=$method_data["example_query"]?></b></pre>

<? // Example Response ?>
<?				if($method_data["example_response"]) { ?>
					<h4>Example Response</h4>
					<pre><?=pretty_json( json_encode($method_data["example_response"])) ?></pre>
<?					} ?>
*/?>

<? // Error Codes ?>
<?				if($method_data["error_codes"]) { ?>
					<h4>Error Codes</h4>
					<table class="table table-striped table-hover table-condensed table-bordered">
						<thead>
							<tr>
								<th class="table_error_code">Code</th>
								<th class="table_error_name">Name</th>
								<th class="table_error_description">Description</th>
							</tr>
						</thead>
						<tbody>
<?
						foreach($method_data["error_codes"] AS $error) {
?>
							<tr>
								<td><?=$error["code"]?></td>
								<td><?=$error["name"]?></td>
								<td><?=$error["description"]?></td>
							</tr>
<?
						}
?>
					</table>
<?					} ?>
					<hr>

<?
			}
?>
				</div>
			</section>
<?
		}
	}
?>
		</div>
	</div>
</body></html>
<?

}

?>