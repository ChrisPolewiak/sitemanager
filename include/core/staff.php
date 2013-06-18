<?

$menu = array();

$_SESSION["sitemanager_params"] = $sitemanager_params;

if ( sm_core_content_user_accesscheck("CORE_ADMINPANEL_READ") ) {
	$menu["8content0"] = array(
		"level" => 0, "name"  => __("core", "MENU__CONTENT"), "url"   => "contentpage.php",
	);
	if ( sm_core_content_user_accesscheck("CORE_CONTENTPAGE_READ") ) {
		$menu["8content0contentpage"] = array(
			"access_type_id" => "CORE_CONTENTPAGE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_PAGES"), "file"  => "contentpage.php",
			"info-short" => "Lista podstron serwisu.",
			"info-long" => "Za pomocą tego modułu można zarządzać strukturą serwisu.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTSECTION_READ") ) {
		$menu["8content0contentsection"] = array(
			"access_type_id" => "CORE_CONTENTSECTION",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_SECTIONS"), "file"  => "contentsection.php",
			"info-short" => "Sekcje dla treści.",
			"info-long" => "Pozwala na przypisanie jednej treści do wielu podstron. Sekcję można przypisać do dowolnej strony w serwisie.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTTEXT_READ") ) {
		$menu["8content0contenttext"] = array(
			"access_type_id" => "CORE_CONTENTTEXT",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_TEXT"), "file"  => "contenttext.php",
			"info-short" => "Zawartość stała serwisu.",
			"info-long" => "Moduł treści może zawierać dowolną treść utworzoną za pomocą edytora. Treść można wzbogacić o zdjęcia oraz dodatkowe załączniki.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTNEWS_READ") ) {
		$menu["8content0contentnews"] = array(
			"access_type_id" => "CORE_CONTENTNEWS",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_NEWS"), "file"  => "contentnews.php",
			"submenu" => array(
				"function" => "contentnewsgroup_fetch_all()",
				"key" => "id_contentnewsgroup",
				"name" => "contentnewsgroup_name",
			),
			"info-short" => "Zawartość zmienna serwisu (typu news).",
			"info-long" => "Moduł treści news zawiera treść, która zawiera elementy z określoną datą. Można go wykorzystać jako moduł do wiadomości, ogłoszeń jak i innych objektów których będzie wieksza ilość jednego typu.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTNEWSGROUP_READ") ) {
		$menu["8content0contentnewsgroup"] = array(
			"access_type_id" => "CORE_CONTENTNEWSGROUP",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_NEWSGROUP"), "file"  => "contentnewsgroup.php",
			"info-short" => "Kategorie zawaerości zmiennej.",
			"info-long" => "Mozna utworzyć dodatkowe kategorie dla treści zmiennej. Np. kategorie wiadomości, ogłoszeń itp.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTMAILTEMPLATE_READ") ) {
		$menu["8content0contentmailtemplate"] = array(
			"access_type_id" => "CORE_CONTENTMAILTEMPLATE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_MAILTEMPLATE"), "file"  => "contentmailtemplate.php",
			"info-short" => "Szablony wiadomości e-mail.",
			"info-long" => "Moduł umożliwia tworzenie szablonów dla wiadomości e-mail wysyłanych z serwisu. Wiadomości mogą być wysyłane do użytkowników serwisu, jak i administratorów.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTFILE_READ") ) {
		$menu["8content0contentfile"] = array(
			"access_type_id" => "CORE_CONTENTFILE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_FILE"), "file"  => "contentfile.php",
			"submenu" => array(
				"array" => "CONTENTCATEGORY_LONGNAME",
				"key" => "id_contentcategory",
				"name" => "contentcategory_name",
			),
			"info-short" => "Zarządzanie plikami i dokumentami serwisu.",
			"info-long" => "Moduł umożliwia zarządzanie wszystkimi dokumenami i plikami serwisu. Pliki i dokumenty mogą być później przypisywane do innych objektów np. do treści stałej i zmiennej.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTCATEGORY_READ") ) {
		$menu["8content0contentcategory"] = array(
			"access_type_id" => "CORE_CONTENTCATEGORY",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_CATEGORY"), "file"  => "contentcategory.php",
			"info-short" => "Zarządzanie kategoriami zasobów serwisu.",
			"info-long" => "Moduł do zarządzania kategoriami plików i dokumentów, które mogą być później przypisywane do innych objektów np. do treści stałej i zmiennej.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTFILE_READ") ) {
		$menu["8content0contentfileassoc"] = array(
			"access_type_id" => "CORE_CONTENTFILE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_FILEASSOC"), "file"  => "contentfileassoc.php",
			"config" => array( "menu_disabled" => 1, ),
			"info-short" => "Załączniki do treści.",
			"info-long" => "Za pomocą tego modułu można przypisać pliki i dokumenty do treści.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTFILE_READ") ) {
		$menu["8content0contentfilepopup"] = array(
			"access_type_id" => "CORE_CONTENTFILE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_FILEPOPUP"), "file"  => "contentfile_popup.php",
			"config" => array( "menu_disabled" => 1, ),
			"info-short" => "Przeglądarka plików.",
		);
	}
	if ( sm_core_content_user_accesscheck("CORE_CONTENTTEMPLATE_READ") ) {
		$menu["8content0contenttemplate"] = array(
			"access_type_id" => "CORE_CONTENTTEMPLATE",
			"level" => "8content0", "name"  => __("core", "MENU__CONTENT_TEMPLATE"), "file"  => "contenttemplate.php",
			"info-short" => "Szablony podstron serwisu WWW.",
			"info-long" => "Edytor który pozwala na modyfikację i utworzenie plików, które będą później wyświetlane w serwisie. Edytor obsługuje szablony Smarty oraz PHP.",
		);
	}
	$menu["8content0searchajax"] = array(
		"access_type_id" => "CORE_ADMIN_CONTENT",
		"level" => "8content0", "name" => __("core", "MENU__SEARCH_AJAX"), "file" => "search-ajax.php",
		"config" => array( "menu_disabled" => 1, ),
	);

	if ( sm_core_content_user_accesscheck("CORE_SETUP_READ") ) {
		$menu["9admin0"] = array(
			"level" => 0, "name"  => __("core", "MENU__CONFIG"), "url"   => "admin.php",
		);
		$menu["9admin0content_user"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_USER"), "file"  => "contentuser.php",
			"info-short" => "Zarządzanie bazą użytkowników.",
			"info-long" => "Moduł służący do zarządzania listą użytkowników. Umożliwia również przypisywanie użytkowników do wybranych grup zabezpieczeń.",
		);
		$menu["9admin0content_usergroup"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_USERGROUP"), "file"  => "contentusergroup.php",
			"info-short" => "Grupy zabezpieczeń.",
			"info-long" => "Grupa zabezpieczeń to zbiór ról serwisu do których będzie mieć dostęp użytkownik przypisany do grupy.",
		);
		$menu["9admin0contenthostallow"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_HOSTALLOW"), "file"  => "contenthostallow.php",
			"info-short" => "Blokowane serwisu z sieci Internet.",
			"info-long" => "Moduł umożliwia zablokowanie dostępu do serwisu dla wszystkich adresów IP nie wymienionych powyżej.",
		);
		$menu["9admin0content_access"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_ACCESS"), "file"  => "contentaccess.php",
			"info-short" => "Role zabezpieczeń.",
			"info-long" => "Role zabezpieczeń to zestawy pojedyńczych uprawnień serwisu.",
		);
		$menu["9admin0contentextra"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_EXTRA"), "file"  => "contentextra.php",
			"info-short" => "Dostosowywanie elementów serwisu.",
			"info-long" => "Moduł umożliwia rozbudowę standarowej zawartości objektów o dodatkowe pola oraz relacje.",
		);
		$menu["9admin0contentcrontab"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_CRONTAB"), "file"  => "contentcrontab.php",
			"info-short" => "Operacje wykonywane co określony czas.",
			"info-long" => "Moduł zarządzający realizacją zadań w tle. Jeśli dany moduł serwisu posiada skrypty automatyczne można w tym menu okreslić częstotliwość ich wykonywania.",
		);
		$menu["9admin0contentfileshowtype"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONTENT_FILESHOWTYPE"), "file"  => "contentfileshowtype.php",
			"info-short" => "Sposób prezentacji zasobów typu zdjęcia.",
			"info-long" => "Moduł umożliwa określenie znaczników które użyte w kodzie pozwalają na przypisane różnych wersji zdjęć w zależności od kontekstu - np. inne zdjęcie w wiadomości na głównej stronie, inne w treści i inne jako załącznik do pobrabnia.",
		);
		$menu["9admin0coretranslation"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CORE_TRANSLATION"), "file"  => "coretranslation.php",
		);
		$menu["9admin0coreplugin"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__PLUGIN"), "file"  => "coreplugin.php",
			"info-short" => "Rozszerzenie systemu o dodatkowe moduły.",
			"info-long" => "Znajduje się tu lista zainstalowanych dodatkowych modułów.",
		);
		$menu["9admin0corebackup"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__BACKUP"), "file"  => "corebackup.php",
			"info-short" => "Kopia zapasowa serwisu.",
			"info-long" => "Moduł pozwala na wykonanie kopii zapasowej całego serwisu wraz z bazą danych.",
		);
		$menu["9admin0coreconfig"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CONFIG"), "file"  => "coreconfig.php",
			"info-short" => "Konfiguracja parametrów serwisu.",
			"info-long" => "Umożliwa konfigurację podstawowych zmiennych.",
		);
		$menu["9admin0coreconfigadminview"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CORE_CONFIG_ADMIN_VIEW"), "file"  => "coreconfigadminview.php",
			"info-short" => "Modyfikacja wyglądu panelu zarządzania.",
			"info-long" => "Umożliwa dostosowanie wyglądu różnych elementów serwisu.",
		);
		$menu["9admin0corechanged"] = array(
			"access_type_id" => "CORE_SETUP",
			"level" => "9admin0", "name"  => __("core", "MENU__CORE_CHANGED"), "file"  => "corechanged.php",
			"info-short" => "Bufor zawierający zmienione rekordy.",
			"info-long" => "Umożliwia przeglądanie zmienionych rekordów i ich przywracanie.",
		);
	}
}
$menu["8content0coretask"] = array(
	"access_type_id" => "CORE_ADMINPANEL",
	"level" => "8content0",
	"name"  => __("core", "MENU__CORE_TASK"),
	"file"  => "coretask.php",
);
$menu["8content0sysinfo"] = array(
	"access_type_id" => "CORE_ADMINPANEL",
	"level" => "8content0",
	"name"  => __("core", "MENU__SYSINFO"),
	"file"  => "sysinfo.php",
	);
$menu["8content0login"] = array(
	"level" => "9admin0", 
	"name"  => __("core", "MENU__LOGIN"),
	"file"  => "login.php",
	"config" => array(
		"menu_disabled" => 1,
	),
);

if(is_array($PLUGIN_STAFF_MENU)) {
	foreach($PLUGIN_STAFF_MENU AS $plugin_name=>$file){
		require $SM_PLUGINS[ $plugin_name ]["dir"]."/".$file;
	}
}

if(is_array($plugin_menu)){
	$menu = array_merge($menu, $plugin_menu);
}

?>