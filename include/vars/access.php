<?

$SYSTEM_DEFINED_ROLEACTION["CORE"] = array(
	"A" => array("tag"=>"ADD", "name"=>"Utworzenie"),
	"R" => array("tag"=>"READ", "name"=>"Odczyt"),
	"W" => array("tag"=>"WRITE", "name"=>"Zmiana"),
	"D" => array("tag"=>"DELETE", "name"=>"Usunięcie"),
	"P" => array("tag"=>"PLUS", "name"=>"Utworzenie relacji do"),
	"M" => array("tag"=>"MINUS", "name"=>"Usunięcie relacji"),
);

$SYSTEM_DEFINED_ROLES["CORE"] = array(
	"CONTENTPAGE"         => array("name"=>"Podstrony", "actions"=>"ARWDPM"),
	"CONTENTSECTION"      => array("name"=>"Sekcje", "actions"=>"ARWDPM"),
	"CONTENTTEXT"         => array("name"=>"Artykuły", "actions"=>"ARWDPM"),
	"CONTENTNEWS"         => array("name"=>"Wiadomości", "actions"=>"ARWDPM"),
	"CONTENTUSER"         => array("name"=>"Użytkownicy", "actions"=>"ARWDPM"),
	"CONTENTUSERGROUP"    => array("name"=>"Grupy użytkowników", "actions"=>"ARWDPM"),
	"CONTENTMAILTEMPLATE" => array("name"=>"Szablony wiadomości e-mail", "actions"=>"ARWDPM"),
	"CONTENTFILE"         => array("name"=>"Multimedia", "actions"=>"ARWDPM"),
	"CONTENTCATEGORY"     => array("name"=>"Kategorie zasobów", "actions"=>"ARWD"),
	"CONTENTTEMPLATE"     => array("name"=>"Szablony podstron", "actions"=>"ARWDPM"),
	"CONTENTNEWSGROUP"    => array("name"=>"Kategorie wiadomości", "actions"=>"ARWDPM"),
	"SETUP"               => array("name"=>"Konfiguracja aplikacji", "actions"=>"RW"),
	"ADMINPANEL"          => array("name"=>"Dostęp do panelu zarządzania", "actions"=>"R"),
);

?>
