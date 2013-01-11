<?

if (preg_match("/\.pdf$/", $REQUEST_URI)) {
	session_cache_limiter("public");
}

define("SessionID", "smsid");

ini_set("session.name", SessionID);
ini_set("session.save_handler","user");
ini_set("session.use_cookies",1);
ini_set("session.use_trans_sid",1);
ini_set("session.cookie_path","/");
ini_set("arg_separator.output", "&amp;");

$SESSION_LIFE = 3600; // konto jest aktywne przez 1 godzine

function sm_session_open() {
	return true;
}
function sm_session_close() {
	return true;
}
function sm_session_read($core_session__sid) {
	$data = core_session_dane( $core_session__sid );
	return ($data!=false ? $data : 0);
}
function sm_session_write($core_session__sid, $data) {
	return core_session_edit($core_session__sid, $data);
}
function sm_session_destroy($core_session__sid) {
	return core_session_delete($core_session__sid);
}
function sm_session_gc() {
	return core_session_delete_old();
}
/*
function sm_session_start() {
	global $SESSION;
	if (isset($_SESSION["SESSION"]) && $_SESSION["SESSION"]){
		$ADMIN_SESSION = $_SESSION["SESSION"];
		session_register("SESSION");
	}
}
*/
function sm_session_get_remote_host() {
	$_SESSION["REMOTE_HOST"]  = (isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"")." ".(isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]?GetHostByAddr($_SERVER["REMOTE_ADDR"]):"" : "");
}

session_set_save_handler(
	"sm_session_open",
	"sm_session_close",
	"sm_session_read",
	"sm_session_write",
	"sm_session_destroy",
	"sm_session_gc"
);

#
##
#

if ( isset($_COOKIE[ SessionID ]) && $_COOKIE[ SessionID ] )
	$core_session__sid = $_COOKIE[ SessionID ];
elseif ( isset($_REQUEST[ SessionID ]) && $_REQUEST[ SessionID ] )
	$core_session__sid = $_REQUEST[ SessionID ];

if(!isset($core_session__sid))
	$core_session__sid = date("YmdHis").substr(md5(uniqid(time())),-10);

session_id($core_session__sid);

if ( !defined("SESSION_DISABLED") ) {
	session_start();
#	register_shutdown_function("sm_session_get_remote_host");
#	register_shutdown_function("sm_session_gc");
}

?>