<?
/**
 * access
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	access
 */

/**
 * @category	access
 * @package		core
 * @version		5.0.0
*/
function sm_core_content_user_accesscheck( $accesstag, $error=false ) {
	global $ROOT_DIR;

	if($_SESSION["content_useracl"][ $accesstag ]) {
		return true;
	}
	elseif($error) {
		ob_clean();
		$row=content_access_get_by_sysname($accesstag);
		$msg  = "<div class=\"error-message\">";
		$msg .= "<div class=\"error-message-title\">".__("core", "Brak dostępu")."</div>\n";
		$msg .= "<div class=\"error-message-body\">\n";
		$msg .= __("core", "Dostęp do tej części systemu wymaga podniesienia uprawnień.")."<br>\n";
		$msg .= __("core", "Wymagany poziom dostępu").":<br>\n";
		$msg .= "- <b>$accesstag</b>";
		if($row) $msg .= " - ".$row["content_access__name"]."<br>\n";
		$msg .= "</div>\n";
		include $ROOT_DIR."/staff/_header.php";
		echo $msg;
		include $ROOT_DIR."/staff/_footer.php";
		exit;
	}
	else
		return false;
}

/**
 * @category	access
 * @package		core
 * @version		5.0.0
*/
function sm_core_auth__create_sessiondata( $content_user__id ) {

	$content_user = content_user_dane( $content_user__id );
	$_SESSION["content_user"] = $content_user;

	// lista grup uzytkownika
	unset($content_usergroup);
	if($result = content_user2content_usergroup_fetch_by_content_user( $content_user__id )) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$content_usergroup[$row["content_usergroup__id"]] = 1;
		}
	}
	$_SESSION["content_usergroup"] = $content_usergroup;

	// lista dostępów dla użytkownika
	unset($content_useracl);
	if($result = content_useracl_fetch_by_user( $content_user__id ) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$tmp = split("\|", $row["content_access__tags"]);
			foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
		}
	}
	// lista dostępów dla grup użytkownika
	foreach($content_usergroup AS $k=>$v) $content_usergroup_flip[]=$k;
	if($result = content_usergroupacl_fetch_by_usergroup( $content_usergroup_flip ) ) {
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			$tmp = split("\|", $row["content_access__tags"]);
			foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
		}
	}
	$_SESSION["content_useracl"] = $content_useracl;

}

/**
 * @category	access
 * @package		core
 * @version		5.0.0
*/
function sm_content_user_access_prepare() {
	global $smarty;

	if(is_array($_SESSION["content_user"])) {

		// lista grup uzytkownika
		unset($content_usergroup);
		unset($_SESSION["content_usergroup"]);
		if($smarty)
			$smarty->assign("content_user", $_SESSION["content_user"]);

		if($result = content_user2content_usergroup_fetch_by_content_user( $_SESSION["content_user"]["content_user__id"] )) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
				$content_usergroup[$row["content_usergroup__id"]] = 1;
			}
			$_SESSION["content_usergroup"] = $content_usergroup;
		}

		// lista dostępów dla użytkownika
		unset($content_useracl);
		unset($content_access);
		if($result = content_useracl_fetch_by_user( $_SESSION["content_user"]["content_user__id"] ) ) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
				$content_access[$row["id_content_access"]]=1;
				$tmp = split("\|", $row["content_access__tags"]);
				foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
			}
		}

		foreach($content_usergroup AS $k=>$v)
			$content_usergroup_flip[]=$k;

		// lista dostępów dla grup użytkownika
		if($result = content_usergroupacl_fetch_by_usergroup( $content_usergroup_flip ) ) {
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
				$content_access[$row["id_content_access"]]=1;
				$tmp = split("\|", $row["content_access__tags"]);
				foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
			}
		}
		$_SESSION["content_access"] = $content_access;
		$_SESSION["content_useracl"] = $content_useracl;
	}
}

?>