<?

if( is_file("../config.ini.php" ) ) {
#	header("Location: /");
#	exit;
}

session_start();

$INSTALL_STEPS = array(
	"index" => "Start",
	"db" => "Baza danych",
	"app" => "Dane aplikacji",
	"access" => "Dostęp",
	"confirm" => "Potwierdź",
	"finish" => "Koniec",
);

require "../include/core/messages.php";
require "../include/core/uifunctions.php";
require "../include/core/phpextended.php";

$_SESSION["step"]["index"] = 1;

if(isset($_POST["action"]["next"])) {
	switch( $_POST["dane"]["step"] ) {
		case "index":
			$next_step = "db";
			$_SESSION["step"][$next_step] = 1;
			header("Location: /install/".$next_step);
			exit;
			break;

		case "db":
			$dane = $_POST["dane"];
					
			if( ! $dane["database_engine"]) {
				$ERROR[] = "Podaj typ serwera baz danych";
			}
			if( ! $dane["database_dbserver"]) {
				$ERROR[] = "Podaj adres serwera baz danych";
			}
			if( ! $dane["database_dbuser"]) {
				$ERROR[] = "Podaj nazwę użytkownika do bazy danych";
			}
			if( ! $dane["database_dbpass"]) {
				$ERROR[] = "Podaj hasło dla użytkownika do bazy danych";
			}
			if( ! $dane["database_dbprefix"]) {
				$ERROR[] = "Podaj prefix dla tabel";
			}
			elseif( !preg_match("/^([a-z]{1})([a-z\d]{1,4})$/", strtolower($dane["database_dbprefix"]), $tmp) ) {
				$ERROR[] = "prefix może składać się wyłacznie z liter i cyfr mi mieć od 2 do 5 znaków";
			}

			if( $dane["database_dbserver"] && $dane["database_dbuser"] && $dane["database_dbpass"] ) {
				try {
					$SM_PDO = new PDO($dane["database_engine"] .":dbname=". $dane["database_dbname"] .";host=". $dane["database_dbserver"], $dane["database_dbuser"], $dane["database_dbpass"]);
				}
				catch(PDOException $e) {
					$ERROR[] = $e->getMessage();
				}
				if( !is_array($ERROR)) {
					$SQL = "SELECT * FROM ".$dane["database_dbprefix"]."_core_session LIMIT 1";
					if ( $SM_PDO->query( $SQL ) ) {
						$ERROR[] = "W bazie znajduje się już instancja SiteManager. Zmień prefix dla tabel, by nie nadpisać istniejących danych.";
					}
					else {
						$_SESSION["config"][ $_POST["dane"]["step"] ] = $dane;
						$next_step = "app";
						$_SESSION["step"][$next_step] = 1;
						header("Location: /install/".$next_step);
						exit;
					}
				}

			}
			break;

		case "app":
			$dane = $_POST["dane"];
					
			if( ! $dane["engine_cacheimagetimeout"]) {
				$ERROR[] = "Podaj domyślny czas przechowywania zdjęć w cache";
			}
			if( strlen($dane["site_adminpanel"])<5 ) {
				$ERROR[] = "Za krótki adres panelu zarządzania";
				$dane["site_adminpanel"]="";
			}
			

			if(!is_array($ERROR)) {
				$_SESSION["config"][ $_POST["dane"]["step"] ] = $dane;
				$next_step = "access";
				$_SESSION["step"][$next_step] = 1;
				header("Location: /install/".$next_step);
				exit;
			}				
			break;

		case "access":
			$dane = $_POST["dane"];
					
			if( strlen($dane["access_username"])<5 ) {
				$ERROR[] = "Identyfikat administratora musi zawierać min 5 znaków";
			}

			if( !preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,}/", $dane["access_password"]) ) {
				$ERROR[] = "Hasło musi spełniać wymogi złożoności. Musi zawierać minimum 5 znaków i składać się z cyfr, liter małych oraz dużych";
			}

			if(!is_array($ERROR)) {
				$_SESSION["config"][ $_POST["dane"]["step"] ] = $dane;
				$next_step = "confirm";
				$_SESSION["step"][$next_step] = 1;
				header("Location: /install/".$next_step);
				exit;
			}				
			break;

		case "confirm":
			if(!is_array($ERROR)) {

				try {
					$SM_PDO = new PDO( $_SESSION["config"]["db"]["database_engine"] .":dbname=". $_SESSION["config"]["db"]["database_dbname"] .";host=". $_SESSION["config"]["db"]["database_dbserver"], $_SESSION["config"]["db"]["database_dbuser"], $_SESSION["config"]["db"]["database_dbpass"]);
				}
				catch(PDOException $e) {
					echo $e->getMessage();
					exit;
				}
				$sqlfile = file("../init.sql") or die("Missing init.sql file");
				
				$sqlquery = "";
				foreach( $sqlfile AS $line ) {
				$line = trim($line);
				$sqlquery .= $line . "\n";
				if(preg_match( "/;$/", $line)) {
# echo "<pre>$sqlquery</pre>";
						$sqlquery = preg_replace("/(%prefix%)/is", $_SESSION["config"]["db"]["database_dbprefix"], $sqlquery);
						$SM_PDO->query( $sqlquery );
						$sqlquery = "";
					}
				}

				$content_user__id = uuid();
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_user_base VALUES ( \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".$_SESSION["config"]["access"]["access_username"]."', \n";
				$sqlquery .= " '".crypt($_SESSION["config"]["access"]["access_password"])."', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " 'Administrator', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " 0, \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " 1, \n";
				$sqlquery .= " 1, \n";
				$sqlquery .= " 0, \n";
				$sqlquery .= " 0, \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " NULL, \n";
				$sqlquery .= " NULL, \n";
				$sqlquery .= " 0, \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				$SM_PDO->query( $sqlquery );

				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_user_extra VALUES ('".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:1()",$sqlquery,$e); }
				$content_access__id1 = uuid();
				
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_access VALUES ( \n";
				$sqlquery .= " '".$content_access__id1."', \n";
				$sqlquery .= " 'System Administration', \n";
				$sqlquery .= " '|CORE_CONTENTUSER_ADD|CORE_CONTENTUSER_READ|CORE_CONTENTUSER_WRITE|CORE_CONTENTUSER_DELETE|CORE_CONTENTUSER_PLUS|CORE_CONTENTUSER_MINUS|CORE_CONTENTUSERGROUP_ADD|CORE_CONTENTUSERGROUP_READ|CORE_CONTENTUSERGROUP_WRITE|CORE_CONTENTUSERGROUP_DELETE|CORE_CONTENTUSERGROUP_PLUS|CORE_CONTENTUSERGROUP_MINUS|CORE_CONTENTMAILTEMPLATE_ADD|CORE_CONTENTMAILTEMPLATE_READ|CORE_CONTENTMAILTEMPLATE_WRITE|CORE_CONTENTMAILTEMPLATE_DELETE|CORE_CONTENTMAILTEMPLATE_PLUS|CORE_CONTENTMAILTEMPLATE_MINUS|CORE_CONTENTTEMPLATE_ADD|CORE_CONTENTTEMPLATE_READ|CORE_CONTENTTEMPLATE_WRITE|CORE_CONTENTTEMPLATE_DELETE|CORE_CONTENTTEMPLATE_PLUS|CORE_CONTENTTEMPLATE_MINUS|CORE_SETUP_READ|CORE_SETUP_WRITE|CORE_ADMINPANEL_READ|', \n";
				$sqlquery .= " 1, \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:2()",$sqlquery,$e); }

				$content_access__id2 = uuid();
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_access VALUES ( \n";
				$sqlquery .= " '".$content_access__id2."', \n";
				$sqlquery .= " 'Content Edit', \n";
				$sqlquery .= " '|CORE_CONTENTPAGE_ADD|CORE_CONTENTPAGE_READ|CORE_CONTENTPAGE_WRITE|CORE_CONTENTPAGE_DELETE|CORE_CONTENTPAGE_PLUS|CORE_CONTENTPAGE_MINUS|CORE_CONTENTSECTION_ADD|CORE_CONTENTSECTION_READ|CORE_CONTENTSECTION_WRITE|CORE_CONTENTSECTION_DELETE|CORE_CONTENTSECTION_PLUS|CORE_CONTENTSECTION_MINUS|CORE_CONTENTTEXT_ADD|CORE_CONTENTTEXT_READ|CORE_CONTENTTEXT_WRITE|CORE_CONTENTTEXT_DELETE|CORE_CONTENTTEXT_PLUS|CORE_CONTENTTEXT_MINUS|CORE_CONTENTNEWS_ADD|CORE_CONTENTNEWS_READ|CORE_CONTENTNEWS_WRITE|CORE_CONTENTNEWS_DELETE|CORE_CONTENTNEWS_PLUS|CORE_CONTENTNEWS_MINUS|CORE_CONTENTFILE_ADD|CORE_CONTENTFILE_READ|CORE_CONTENTFILE_WRITE|CORE_CONTENTFILE_DELETE|CORE_CONTENTFILE_PLUS|CORE_CONTENTFILE_MINUS|CORE_CONTENTCATEGORY_ADD|CORE_CONTENTCATEGORY_READ|CORE_CONTENTCATEGORY_WRITE|CORE_CONTENTCATEGORY_DELETE|CORE_CONTENTNEWSGROUP_ADD|CORE_CONTENTNEWSGROUP_READ|CORE_CONTENTNEWSGROUP_WRITE|CORE_CONTENTNEWSGROUP_DELETE|CORE_CONTENTNEWSGROUP_PLUS|CORE_CONTENTNEWSGROUP_MINUS|CORE_ADMINPANEL_READ|', \n";
				$sqlquery .= " 0, \n";
				$sqlquery .= " '', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:3()",$sqlquery,$e); }

				$content_usergroup__id1 = uuid();
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_usergroup VALUES ( \n";
				$sqlquery .= " '".$content_usergroup__id1."', \n";
				$sqlquery .= " 'Administrator systemu', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:4()",$sqlquery,$e); }

				$content_usergroup__id2 = uuid();
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_usergroup VALUES ( \n";
				$sqlquery .= " '".$content_usergroup__id2."', \n";
				$sqlquery .= " 'Edytor tresci', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:5()",$sqlquery,$e); }
				
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_usergroupacl VALUES ( \n";
				$sqlquery .= " '".$content_access__id1."', \n";
				$sqlquery .= " '".$content_usergroup__id1."', \n";
				$sqlquery .= " 1, \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:6()",$sqlquery,$e); }

				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_usergroupacl VALUES ( \n";
				$sqlquery .= " '".$content_access__id2."', \n";
				$sqlquery .= " '".$content_usergroup__id2."', \n";
				$sqlquery .= " 1, \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:7()",$sqlquery,$e); }
				
				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_user2content_usergroup VALUES ( \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".$content_usergroup__id1."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:8()",$sqlquery,$e); }

				$sqlquery  = "INSERT INTO ".$_SESSION["config"]["db"]["database_dbprefix"]."_content_user2content_usergroup VALUES ( \n";
				$sqlquery .= " '".$content_user__id."', \n";
				$sqlquery .= " '".$content_usergroup__id2."', \n";
				$sqlquery .= " '".time()."', \n";
				$sqlquery .= " '".$content_user__id."') \n";
# echo "<pre>$sqlquery</pre>";
				try { $result = $GLOBALS["SM_PDO"]->query($sqlquery); } catch(PDOException $e) { sqlerr("install:9()",$sqlquery,$e); }

				$fp = fopen("../config.ini.php","w");
				fputs($fp, "<"."?"."/"."*"."\n\n");
				fputs($fp, "[database]\n");
				fputs($fp, "engine = ".$_SESSION["config"]["db"]["database_engine"]."\n");
				fputs($fp, "dbserver = ".$_SESSION["config"]["db"]["database_dbserver"]."\n");
				fputs($fp, "dbuser = ".$_SESSION["config"]["db"]["database_dbuser"]."\n");
				fputs($fp, "dbpass = ".$_SESSION["config"]["db"]["database_dbpass"]."\n");
				fputs($fp, "dbname = ".$_SESSION["config"]["db"]["database_dbname"]."\n");
				fputs($fp, "dbprefix = ".$_SESSION["config"]["db"]["database_dbprefix"]."\n");
				fputs($fp, "\n");
/*
				fputs($fp, "[language]\n");
				foreach($_SESSION["config"]["app"]["SM_TRANSLATION_LANGUAGES"] AS $k=>$v) {
					fputs($fp, "lang[".$k."] = ".$SM_TRANSLAGE_LANGUAGES[$k]."\n");
				}
				fputs($fp, "\n");
*/
				fputs($fp, "[engine]\n");
				fputs($fp, "test_mode = false\n");
				fputs($fp, "cache_image_timeout = ".($_SESSION["config"]["app"]["engine_cacheimagetimeout"] * 86400)."\n");
				fputs($fp, "data_encryption_key = ".md5(microtime())."\n");
				fputs($fp, "site_adminpanel = ".$_SESSION["config"]["app"]["site_adminpanel"]."\n");
				fputs($fp, "\n");
				fputs($fp, "[site]\n");
				fputs($fp, "server_name = ".$_SESSION["config"]["app"]["site_servername"]."\n");
				fputs($fp, "site_title = ".$_SESSION["config"]["app"]["site_servername"]."\n");
				fputs($fp, "site_description = ".$_SESSION["config"]["app"]["site_description"]."\n");
				fputs($fp, "site_keywords = ".$_SESSION["config"]["app"]["site_keywords"]."\n");
				fputs($fp, "mail_addr_admin = ".$_SESSION["config"]["app"]["site_mailaddradmin"]."\n");
				fputs($fp, "server_name = ".$_SESSION["config"]["app"]["site_servername"]."\n");
				fputs($fp, "\n");
				fputs($fp, "[support]\n");
				fputs($fp, "customercode = \n");
				fputs($fp, "serialnumber = \n");
				fputs($fp, "*"."/"."?".">");
				header("Location: /".$_SESSION["config"]["app"]["site_adminpanel"]);
				exit;
			}
			break;
	}
}

$step = "index";
if ( preg_match("/^\/*([^\?]*)/", $_SERVER["REQUEST_URI"], $tmp)) {
	$url = $tmp[1];
}

if (ereg("\/", $url))
	$step = substr($url,strpos($url,"/")+1);

if( ! $_SESSION["step"][$step]) {
	header("Location: /install");
	exit;
}

foreach($_SESSION["config"][$step] AS $k=>$v) {
	$dane[$k] = $dane[$k] ? $dane[$k] : $v;
}

require "_install_header.php";

?>
				<form action="" method=post enctype="multipart/form-data" id="sm-form">

<? if( isset($GLOBALS["ERROR"]) && $GLOBALS["ERROR"] ) { ?>
				<div class="alert alert-error">
					<?=join("<br>",$GLOBALS["ERROR"])?>
				</div>
<? } ?>

<?

	switch($step) {
		default: case "index":
?>
					<fieldset class="no-legend">
						<h4>Dziękujemy za wybranie programu SiteManager</h4>
						<p>
							W kilku krokach pomożemy Ci przygotować wstępną konfigurację programu.<br>
							<br>
							<i>zespół SiteManager</i><br>
							<br>
							<a href="/install/db">Zaczynamy</a><br>
						</p>
					</fieldset>

					<div class="btn-toolbar">
						<input type="hidden" id="step" name="step" value="<?=$step?>">
						<a class="btn btn-normal btn-info" id="action-next"><i class="icon-play icon-white"></i>&nbsp;DALEJ</a>
					</div>
<?
		break;

		case "db":
?>
					<fieldset class="no-legend">
						<h4>Konfiguracja ustawień bazy danych</h4>

<?
	$inputfield_options=array();
	$inputfield_options["mysql"]="MySQL";
#	$inputfield_options["pgsql"]="PostgreSQL";
#	$inputfield_options["mssql"]="Microsoft SQL Server";
?>
						<?=sm_inputfield( array("type"=>"select", "title"=>"Typ bazy danych", "help"=>"Wybierz bazę danych jakiej zamierzasz użyć", "id"=>"dane_database_engine", "name"=>"dane[database_engine]", "value"=>$dane["database_engine"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>$inputfield_options, "xss_secured"=>true) ) ?>
<?
	$dane["database_dbserver"] = $dane["database_dbserver"] ? $dane["database_dbserver"] : "localhost";
?>
						<div class="row-float">
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Adres serwera bazy danych", "help"=>"Wprowadź adres serwera gdzie się znajduje baza danych", "id"=>"dane_database_dbserver", "name"=>"dane[database_dbserver]", "value"=>$dane["database_dbserver"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Nazwa bazy danych", "help"=>"Wprowadź nazwę bazy danych", "id"=>"dane_database_dbname", "name"=>"dane[database_dbname]", "value"=>$dane["database_dbname"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
						</div>
						<div class="row-float">
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Nazwa użytkownika", "help"=>"Wprowadź nazwę użytkownika do bazy danych", "id"=>"dane_database_dbuser", "name"=>"dane[database_dbuser]", "value"=>$dane["database_dbuser"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Hasło", "help"=>"Wprowadź hasło użytkownika bazy danych", "id"=>"dane_database_dbpass", "name"=>"dane[database_dbpass]", "value"=>$dane["database_dbpass"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
						</div>
<?
	$dane["database_dbprefix"] = $dane["database_dbprefix"] ? $dane["database_dbprefix"] : "sm";
?>
						<?=sm_inputfield( array("type"=>"text", "title"=>"Prefix dla tabel", "help"=>"Wprowadź kilku znakowy ciąg, który będzie podany przed każdą tabelą. Umożliwi Ci to instalację kilku edycji SiteManager jednocześnie na tej samej bazie danych", "id"=>"database_dbprefix", "name"=>"dane[database_dbprefix]", "value"=>$dane["database_dbprefix"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
					</fieldset>

					<div class="btn-toolbar">
						<input type="hidden" id="step" name="step" value="<?=$step?>">
						<a class="btn btn-normal btn-info" id="action-next"><i class="icon-play icon-white"></i>&nbsp;DALEJ</a>
					</div>
<?
		break;

		case "app":
?>
					<fieldset class="no-legend">
						<h4>Konfiguracja ustawień aplikacji</h4>

						<div class="row-float">
							<div class="span6">
								<?=sm_inputfield( array("type"=>"checkbox", "title"=>"Tryb developerski", "help"=>"W tym trybie wyświetlane są wszystkie komunikaty błędów", "id"=>"dane_engine_testmode", "name"=>"dane[engine_testmode]", "value"=>$dane["engine_testmode"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
<?
$dane["engine_cacheimagetimeout"] = $dane["engine_cacheimagetimeout"] ? $dane["engine_cacheimagetimeout"] : 24;
?>
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Domyślny czas cache dla zdjęć (godziny)", "help"=>"Ile godzin mają być przechowywane dane w cache zdjęć", "id"=>"dane_engine_cacheimagetimeout", "name"=>"dane[engine_cacheimagetimeout]", "value"=>$dane["engine_cacheimagetimeout"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
						</div>
<?
$dane["site_servername"] = $dane["site_servername"] ? $dane["site_servername"] : "SiteManager";
?>
						<div class="row-float">
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Nazwa serwisu", "help"=>"Jak będzie się nazywać witryna którą uruchamiasz", "id"=>"dane_site_servername", "name"=>"dane[site_servername]", "value"=>$dane["site_servername"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
							<div class="span6">
								<?=sm_inputfield( array("type"=>"text", "title"=>"Adres e-mail administratora", "help"=>"Adres e-mail używany w przypadku problemów z witryną", "id"=>"dane_site_mailaddradmin", "name"=>"dane[site_mailaddradmin]", "value"=>$dane["site_mailaddradmin"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
							</div>
						</div>

<?
$chars = "abcdefghijklmnopqrstuwvzyz1234567890";
if( strlen($dane["site_adminpanel"]<5) ) {
	$dane["site_adminpanel"] = "";
	for($i=1;$i<=8;$i++) {
		$dane["site_adminpanel"] .= $chars[intval(rand(0,36))];
	}
}
?>
						<?=sm_inputfield( array("type"=>"text", "title"=>"Adres panelu zarządzania", "help"=>"Podaj adres panelu zarządzania. Minimum 5 znaków", "id"=>"dane_site_adminpanel", "name"=>"dane[site_adminpanel]", "value"=>$dane["site_adminpanel"], "size"=>"small", "disabled"=>false, "validation"=>false, "prepend"=>"https://adres_serwera/", "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
						<?=sm_inputfield( array("type"=>"textarea", "title"=>"Opis serwisu", "help"=>"Podaj opis uruchamianej witruny (treść pojawi się w nagłówkach stron)", "id"=>"dane_site_description", "name"=>"dane[site_description]", "value"=>$dane["site_description"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
						<?=sm_inputfield( array("type"=>"textarea", "title"=>"Słowa kluczowe", "help"=>"Podaj słowa kluczowe dla uruchamianej witruny (pojawią się w nagłówkach stron)", "id"=>"dane_site_keywords", "name"=>"dane[site_keywords]", "value"=>$dane["site_keywords"], "size"=>"block-level", "disabled"=>false, "validation"=>false, "prepend"=>false, "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
<?
/*

	$inputfield_options=array();
	foreach($SM_TRANSLATION_LANGUAGES AS $k=>$v) {
		$inputfield_options[ $k ] = $v;
	}
	$dane["SM_TRANSLATION_LANGUAGES"] = $dane["SM_TRANSLATION_LANGUAGES"] ? $dane["SM_TRANSLATION_LANGUAGES"] : array("pl"=>1);
?>
						<?=sm_inputfield( "checkbox-multi", "Dostępne języki dla danych", "", "dane_SM_TRANSLATION_LANGUAGES", "dane[SM_TRANSLATION_LANGUAGES]", $dane["SM_TRANSLATION_LANGUAGES"], "block-level", $disabled=false, $validation=false, $prepend=false, $append=false, $rows=1, $inputfield_options);?>
<?
*/
?>
					</fieldset>

					<div class="btn-toolbar">
						<input type="hidden" id="step" name="step" value="<?=$step?>">
						<a class="btn btn-normal btn-info" id="action-next"><i class="icon-play icon-white"></i>&nbsp;DALEJ</a>
					</div>
<?
		break;

		case "access":
?>
					<fieldset class="no-legend">
						<h4>Konfiguracja ustawień dostępu</h4>

<?
$dane["access_username"] = $dane["access_username"] ? $dane["access_username"] : "administrator";
?>
						<?=sm_inputfield( array("type"=>"text", "title"=>"Identyfikator administratora serwisu", "help"=>"Podaj nazwę logowania dla konta administratora", "id"=>"dane_access_username", "name"=>"dane[access_username]", "value"=>$dane["access_username"], "size"=>"medium", "disabled"=>false, "validation"=>false, "prepend"=>"", "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>
						<?=sm_inputfield( array("type"=>"text", "title"=>"Hasło administratora serwisu", "help"=>"Podaj hasło dla konta administratora", "id"=>"dane_access_password", "name"=>"dane[access_password]", "value"=>$dane["access_password"], "size"=>"medium", "disabled"=>false, "validation"=>false, "prepend"=>"", "append"=>false, "rows"=>1, "options"=>"", "xss_secured"=>true) ) ?>

					</fieldset>

					<div class="btn-toolbar">
						<input type="hidden" id="step" name="step" value="<?=$step?>">
						<a class="btn btn-normal btn-info" id="action-next"><i class="icon-play icon-white"></i>&nbsp;DALEJ</a>
					</div>
<?
		break;

		case "confirm":
?>
					<fieldset class="no-legend">
						<h4>Weryfikacja danych</h4>

						<table class="table">
							<thead>
								<tr>
									<th>Pole</th>
									<th>Wartość</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Silnik bazy danych</td>
									<td><?=$_SESSION["config"]["db"]["database_engine"]?></td>
								</tr>
								<tr>
									<td>Adres serwera bazy danych</td>
									<td><?=$_SESSION["config"]["db"]["database_dbserver"]?></td>
								</tr>
								<tr>
									<td>Login do serwera bazy danych</td>
									<td><?=$_SESSION["config"]["db"]["database_dbuser"]?></td>
								</tr>
								<tr>
									<td>Hasło do serwera bazy danych</td>
									<td><?=$_SESSION["config"]["db"]["database_dbpass"]?></td>
								</tr>
								<tr>
									<td>Nazwa bazy danych</td>
									<td><?=$_SESSION["config"]["db"]["database_dbname"]?></td>
								</tr>
								<tr>
									<td>Prefix dla tabel</td>
									<td><?=$_SESSION["config"]["db"]["database_dbprefix"]?></td>
								</tr>

								<tr>
									<td>Timeout cache dla zdjęć</td>
									<td><?=$_SESSION["config"]["app"]["engine_cacheimagetimeout"]?> h</td>
								</tr>
								<tr>
									<td>Nazwa serwisu</td>
									<td><?=$_SESSION["config"]["app"]["site_servername"]?></td>
								</tr>
								<tr>
									<td>Adres e-mail administratora</td>
									<td><?=$_SESSION["config"]["app"]["site_mailaddradmin"]?></td>
								</tr>
								<tr>
									<td>Opis serwisu (META)</td>
									<td><?=$_SESSION["config"]["app"]["site_description"]?></td>
								</tr>
								<tr>
									<td>Słowa kluczowe dla serwisu (META)</td>
									<td><?=$_SESSION["config"]["app"]["site_keywords"]?></td>
								</tr>
<?/*
								<tr>
									<td>Języki dla treści</td>
									<td>
										<ul>
<? foreach($_SESSION["config"]["app"]["SM_TRANSLATION_LANGUAGES"] AS $k=>$v) { ?>
											<li><?=$LANGUAGES[$k]?></li>
<? } ?>
										<ul>
									</td>
								</tr>
*/?>
								<tr>
									<td>Identyfikator administratora</td>
									<td><?=$_SESSION["config"]["access"]["access_username"]?></td>
								</tr>
								<tr>
									<td>Hasło administratora</td>
									<td><?=$_SESSION["config"]["access"]["access_password"]?></td>
								</tr>
							</tbody>
						</table>
					</fieldset>

					<div class="btn-toolbar">
						<input type="hidden" id="step" name="step" value="<?=$step?>">
						<a class="btn btn-normal btn-info" id="action-next"><i class="icon-play icon-white"></i>&nbsp;POTWIERDZAM</a>
					</div>
<?
		break;


	}
?>
<script>
$().ready(function(){
	$('#action-next').unbind();
	$('#action-next').bind('click',function(){
		var currentstep = $('#step').val();
		$('#sm-form').append('<input type="hidden" name="dane[step]" value="'+ currentstep +'">');
		$('#sm-form').append('<input type="hidden" name="action[next]" value=1>');
		$('#sm-form').submit();
	});
});
</script>
				</form>
<?

require "_install_footer.php";
?>