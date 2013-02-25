<?
/**
 * session
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	session
 */

$SESSION_LIFE = 3600; // konto jest aktywne przez 1 godzine

if (preg_match("/\.pdf$/", $REQUEST_URI)) {
	session_cache_limiter("public");
}

/*
if (session_id()) {
	session_unset();
	session_destroy();
}
*/

define("SessionID", "smsid");
ini_set("session.name", SessionID);
ini_set("session.save_handler", "user");
ini_set("session.use_trans_sid", false);
ini_set("session.use_only_cookies", true);
ini_set("session.cookie_path", "/");
ini_set("session.gc_probability", "1");
ini_set("session.gc_divisor", "1");
ini_set("session.gc_maxlifetime", $SESSION_LIFE);
ini_set("arg_separator.output", "&amp;");


function sm_session_open_default() { return true; }

function sm_session_close_default() { return true; }


/**
 * @category	session
 * @package		core
 * @version		5.0.0
*/
function sm_session_read($core_session__sid) {
	$data = core_session_dane( $core_session__sid );
	$data = json_decode( $data, 1 );
	return $data;
}
function sm_session_read_default() { return true; }

/**
 * @category	session
 * @package		core
 * @version		5.0.0
*/
function sm_session_write() {
	global $core_session__sid;
	return core_session_edit($core_session__sid, addslashes(json_encode($_SESSION)) );
}
function sm_session_write_default() { return true; }

/**
 * @category	session
 * @package		core
 * @version		5.0.0
*/
function sm_session_destroy($core_session__sid) {
	return core_session_delete($core_session__sid);
}

/**
 * @category	session
 * @package		core
 * @version		5.0.0
*/
function sm_session_gc() {
	return core_session_delete_old();
}

/**
 * @category	session
 * @package		core
 * @version		5.0.0
*/
function sm_session_get_remote_host() {
	$_SESSION["REMOTE_HOST"]  = (isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"")." ".(isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]?GetHostByAddr($_SERVER["REMOTE_ADDR"]):"" : "");
}

session_set_save_handler(
	"sm_session_open_default",
	"sm_session_close_default",
	"sm_session_read_default",
	"sm_session_write_default",
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

if(!isset($core_session__sid)) {
	$core_session__sid = date("YmdHis").substr(md5(uniqid(time())),-10);
}

session_id($core_session__sid);

if ( !defined("SESSION_DISABLED") ) {
	session_start();
	$_SESSION = sm_session_read( $core_session__sid );
	register_shutdown_function("sm_session_write");
	register_shutdown_function("sm_session_gc");
}

$_SESSION["sm_core"] = array(
	"page" => $page,
);

?>