<?

$action = $_REQUEST["action"];
$backto = $_REQUEST["backto"];

if( isset($action["login"]))
{

	if($_SESSION["formtoken"]["staff-login"] != $_REQUEST["formtoken"])
		$ERROR[] = __("core", "FORM_SECURITY_ERROR");

	else
	{

		$user_login    = trim($_POST["user_login"]);
		$user_password = trim($_POST["user_password"]);

		if ( !$user_login || !$user_password )
			$ERROR[] = __("core", "LOGIN__EROR_MISSING_USERNAME_AND_PASSWORD");

		else
		{
			if ( ! $tmp_content_user = content_user_get_by_username( $user_login ) )
				$ERROR[] = __("core", "LOGIN__ERROR_WRONG_USERNAME");

			else
			{
				$delta = 0;
				if( $tmp_content_user["content_user__login_falsecount"]>=200 )
				{
					$delta_req = 3*60;
					$delta = time() - $tmp_content_user["content_user__login_false"];
				}
				if ($delta < $delta_req)
					$ERROR[] = __("core", "LOGIN__ERROR_PASSWORD_LOCK", ($delta_req-$delta) );

				else
				{
					$haslo = crypt( $user_password, $tmp_content_user["content_user__password"] );
					if ($haslo != $tmp_content_user["content_user__password"] )
					{
						$ERROR[] = __("core", "LOGIN__ERROR_BAD_PASSWORD");
						content_user_login_status_update( $tmp_content_user["content_user__id"], false );
					}
					else
					{
						if( $tmp_content_user["content_user__admin_hostallow"] && !checkaccess_by_hostallow($tmp_content_user["content_user__admin_hostallow"]) )
							$ERROR[] = __("core", "LOGIN__ERROR_ACCESS_DENIED_FROM_IP");

						else
						{
							if($tmp_content_user["content_user__status"]==3)
								$ERROR[] = __("core", "LOGIN__ERROR_ACCOUNT_DISABLED");

							else
							{
								content_user_login_status_update( $tmp_content_user["content_user__id"], true );

								unset($_SESSION["content_usergroup"]);
								// lista grup uzytkownika
								if($result = content_user2content_usergroup_fetch_by_content_user( $tmp_content_user["content_user__id"] ))
								{
									while($row=$result->fetch(PDO::FETCH_ASSOC))
										$content_usergroup[$row["content_usergroup__id"]] = 1;
								}

								unset($content_useracl);
								unset($_SESSION["content_useracl"]);
								// lista dostępów dla użytkownika

								if($result = content_user2content_access_fetch_by_user( $tmp_content_user["content_user__id"] ) )
								{
									while($row=$result->fetch(PDO::FETCH_ASSOC))
									{
										$tmp = split("\|", $row["content_access__tags"]);
										foreach($tmp AS $k=>$v){ if($v) $content_useracl[$v]=1; }
									}
								}

								// lista dostępów dla grup użytkownika
								foreach($content_usergroup AS $k=>$v)
									$content_usergroup_flip[]=$k;

								if($result = content_usergroup2content_access_fetch_by_usergroup( $content_usergroup_flip ) )
								{
									while($row=$result->fetch(PDO::FETCH_ASSOC))
									{
										$tmp = split("\|", $row["content_access__tags"]);
										foreach($tmp AS $k=>$v)
										{
											if($v)
												$content_useracl[$v]=1;
										}
									}
								}

								$_SESSION["content_useracl"] = $content_useracl;

								if( ! $content_useracl["CORE_ADMINPANEL_READ"] )
									$ERROR[] = __("core", "LOGIN__ERROR_ACL_MISSING");

								else {
									$_SESSION["content_user"] = $tmp_content_user;
									$_SESSION["content_usergroup"] = $content_usergroup;

									if($backto)
										header("Location: ".base64_decode($backto));

									else
										header("Location: /".$SM_ADMIN_PANEL."?admin_lang=$admin_lang");

									exit;
								}
							}
						}
					}
				}
			}
		}
	}
}

include "_header5.php";
?>
<style>
body {
	background: url("/staff/img/login-bg.png") repeat-x !important;
}
.login-form-wrapper {
	width:100%;
}
.login-form {
	margin:109px auto 0px auto;
	width:500px !important;
}
.login-title {
	position: absolute;
	margin-left: 15px;
	margin-top: 19px;
	font-weight: bold;
	font-size: 17px;
}
.login-brand {
	position: absolute;
	margin-left: 270px;
	margin-top: 18px;
}
.login-version {
	position: absolute;
	margin-left: 423px;
	margin-top: 36px;
	font-size: 10px;
}
.login-body {
	background: url("/staff/img/login-body.jpg") no-repeat;
	overflow: hidden;
	width: 100%;
	height:221px;
}
.login-header {
	background: #fff;
	height:55px;
}
fieldset {
	background: none !important;
	border: none !important;
}
form {
	margin-top: 84px;
}
.login-alert {
	position:absolute;
	width:480px;
	padding:10px;
}
.alert {
	background: #c00 !important;
	color:#fff !important;
	border:none !important;
}
</style>

<div class="login-form-wrapper">
	<div class="login-form">
		<div class="login-header">
			<div class="login-title">
				<?=__("core", "LOGIN__BOX_TITLE")?>
			</div>
			<div class="login-brand">
				<a href="/<?=$SM_ADMIN_PANEL?>"><img src="/staff/img/sitemanager-logo-white.png" alt="sitemanager" border=0 style="position:absolute;margin-top:-5px"></a>
			</div>
			<div class="login-version">
				version: <?=$SOFTWARE_INFORMATION["version"]?></b>
			</div>
		</div>
		<div class="login-body">
<?
if (is_array($ERROR))
{
?>
			<div class="login-alert">
				<div class="alert alert-error">
					<?=join("<br>",$GLOBALS["ERROR"])?>
				</div>
			</div>
<?
}
?>

<?/*
			<form name=login action="<?=preg_match("/logout.*$/", $_SERVER["REQUEST_URI"]) ? "/".$SM_ADMIN_PANEL."/" : $_SERVER["REQUEST_URI"]?>" method=post>
*/?>
			<form name=login action="" method=post>

				<fieldset>
					<div class="row-fluid">
						<div class="span4">
							<b><?=__("CORE","LOGIN__FIELD_USERNAME")?></b>
						</div>
						<div class="span8">
							<?=sm_inputfield(array(
								"type"=>"text",
								"title"=>"",
								"help"=>"",
								"id"=>"user_login",
								"name"=>"user_login",
								"value"=>$_POST["user_login"],
								"size"=>"block-level",
								"disabled"=>false,
								"validation"=>false,
								"prepend"=>false,
								"append"=>false,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
								))?>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span4">
							<b><?=__("CORE","LOGIN__FIELD_PASSWORD")?></b>
						</div>
						<div class="span8">
							<?=sm_inputfield(array(
								"type"=>"password",
								"title"=>"",
								"help"=>"",
								"id"=>"user_password",
								"name"=>"user_password",
								"value"=>$_POST["user_password"],
								"size"=>"block-level",
								"disabled"=>false,
								"validation"=>false,
								"prepend"=>false,
								"append"=>false,
								"rows"=>1,
								"options"=>"",
								"xss_secured"=>true
								))?>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span4">
						</div>
						<div class="span8">
							<input type="submit" class="btn btn-normal btn-info" value="<?=__("CORE","LOGIN__BUTTON_LOGIN")?>" name="action[login]">
						</div>
					</div>
				</fieldset>
				<input type=hidden name="backto" value="<?=sm_secure_string_xss($backto)?>">
				<input type="hidden" name="formtoken" value="<?=$_SESSION["formtoken"]["staff-login"]=md5(rand(0,time()));?>">
			</form>
		</div>
		<div class="login-footer">
			<?=$SOFTWARE_INFORMATION["application"]?> <?=$SOFTWARE_INFORMATION["version"]?> (<?=$SOFTWARE_INFORMATION["date"]?>), Copyright root.pl Krzysztof Polewiak, Licenced on GPL Licence
		</div>
	</div>
</div>

		
			
<script language="JavaScript"><!--
	document.login.user_login.select();
	document.login.user_login.focus();
//--></script>

			</div>

			<br>
		</div>
	</div>
<? include "_footer5.php"; ?>