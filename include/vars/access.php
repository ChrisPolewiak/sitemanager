<?

$SYSTEM_DEFINED_ROLEACTION["CORE"] = array(
	"A" => array("tag"=>"ADD", "name"=>__("core", "Utworzenie")),
	"R" => array("tag"=>"READ", "name"=>__("core", "Odczyt")),
	"W" => array("tag"=>"WRITE", "name"=>__("core", "Zmiana")),
	"D" => array("tag"=>"DELETE", "name"=>__("core", "Usunięcie")),
	"P" => array("tag"=>"PLUS", "name"=>__("core", "Utworzenie relacji do")),
	"M" => array("tag"=>"MINUS", "name"=>__("core", "Usunięcie relacji")),
);

$SYSTEM_DEFINED_ROLES["CORE"] = array(
	"CONTENTPAGE"         => array("name"=>__("core", "Podstrony"), "actions"=>"ARWDPM"),
	"CONTENTSECTION"      => array("name"=>__("core", "Sekcje"), "actions"=>"ARWDPM"),
	"CONTENTTEXT"         => array("name"=>__("core", "Artykuły"), "actions"=>"ARWDPM"),
	"CONTENTNEWS"         => array("name"=>__("core", "Wiadomości"), "actions"=>"ARWDPM"),
	"CONTENTUSER"         => array("name"=>__("core", "Użytkownicy"), "actions"=>"ARWDPM"),
	"CONTENTUSERGROUP"    => array("name"=>__("core", "Grupy użytkowników"), "actions"=>"ARWDPM"),
	"CONTENTMAILTEMPLATE" => array("name"=>__("core", "Szablony wiadomości e-mail"), "actions"=>"ARWDPM"),
	"CONTENTFILE"         => array("name"=>__("core", "Multimedia"), "actions"=>"ARWDPM"),
	"CONTENTCATEGORY"     => array("name"=>__("core", "Kategorie zasobów"), "actions"=>"ARWD"),
	"CONTENTTEMPLATE"     => array("name"=>__("core", "Szablony podstron"), "actions"=>"ARWDPM"),
	"CONTENTNEWSGROUP"    => array("name"=>__("core", "Kategorie wiadomości"), "actions"=>"ARWDPM"),
	"SETUP"               => array("name"=>__("core", "Konfiguracja aplikacji"), "actions"=>"RW"),
	"ADMINPANEL"          => array("name"=>__("core", "Dostęp do panelu zarządzania"), "actions"=>"R"),
);

?>