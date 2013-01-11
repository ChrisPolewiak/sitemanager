<?

$MONTH_NAMES = array("", "stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
$MONTH_NAMES2 = array("", "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");
$DAY_NAMES = array("poniedziałek", "wtorek", "środa", "czwartek", "piątek", "sobota", "niedziela");

$CONTENT_USER_STATUS_ARRAY = array(
	1 => array("name"=>"Nowy - nieaktywny"),
	2 => array("name"=>"Aktywny"),
	3 => array("name"=>"Zablokowany"),
);
define( "CONTENT_USER_STATUS_NEW", 1);
define( "CONTENT_USER_STATUS_ACTIVE", 2);
define( "CONTENT_USER_STATUS_DISABLED", 3);


$PHOTOSETUP["pressphoto"] = array(
	1=>array("name"=>"src", "width"=>0, "height"=>0, "proportion"=>""),
	2=>array("name"=>"thumb", "width"=>150, "height"=>200, "proportion"=>"w"),
	);

$CONTENT_FILEASSOC_OPENTYPE_ARRAY = array(
	1 => array("name" => "wyświetl", "title" => "Wyświetl plik na stronie" ),
	2 => array("name" => "pobierz", "title" => "Plik do pobrania" ),
	3 => array("name" => "popup", "title" => "Wyświetl w oknie typu popup" ),
	4 => array("name" => "layer", "title" => "Wyświetl na warstwie" ),
	);

$ADMINLOG_STATUS_ARRAY = array(
	1 => array("name" => "LOGIN", "title" => "Zalogowanie do systemu" ),
	2 => array("name" => "BAD PASSWORD", "title" => "Nieprawidłowe hasło" ),
	3 => array("name" => "LOGOUT", "title" => "Wylogowanie z systemu" ),
	4 => array("name" => "TIMEOUT", "title" => "Automatyczne wylogowanie z systemu" ),
	5 => array("name" => "ACTION", "title" => "Akcja" ),
	6 => array("name" => "BLOCKED", "title" => "Konto zablokowane" ),
	7 => array("name" => "BLOCKED_BAD_PASS", "title" => "Automatyczne zablokowane po złych haslach" ),
	);

$ADMIN_STATUS_ARRAY = array(
	1 => array("name" => "ENABLED", "title" => "Aktywny" ),
	2 => array("name" => "DISABLED", "title" => "Zablokowany" ),
	);

define( "ADMINLOG_STATUS_LOGIN", 1);
define( "ADMINLOG_STATUS_BAD", 2);
define( "ADMINLOG_STATUS_LOGOUT", 3);
define( "ADMINLOG_STATUS_TIMEOUT", 4);
define( "ADMINLOG_STATUS_ACTION", 5);
define( "ADMINLOG_STATUS_BLOCKED", 6);
define( "BLOCKED_BAD_PASS", 7);
?>
