<?php
/*

# Language definitions for pl (Polski)

[_DEFINE_]
LANGUAGE_CODE = "pl"
LANGUAGE_NAME = "Polski"

[BUTTON]
BUTTON__SAVE = "zapisz"
BUTTON__DELETE = "usuń"
BUTTON__BACK_TO_LIST = "wróć do listy"
BUTTON__RESTORE = "odtwórz"
BUTTON__ADD_NEW = "dodaj nowy rekord"
BUTTON__ADD = "dodaj"
BUTTON__LOGOUT = "wyloguj się"
BUTTON__MARSHAL = "uporządkuj dane"

[CORE_TRANSLATION]
CORE_TRANSLATION__SECTION_CONFIG = "Definicja języka"
CORE_TRANSLATION__FIELD_CONFIG_NAME = "Język"
CORE_TRANSLATION__SECTION = "Sekcja"
CORE_TRANSLATION__FIELD_NAME = "Nazwa pola"
CORE_TRANSLATION__FIELD_VALUE = "Wartość pola"

[DATATABLE]
DATATABLE__NEW_RECORD = "Nowy rekord"
DATATABLE__SCRIPT_PROCESSING = "Proszę czekać..."
DATATABLE__SCRIPT_SLENGTHMENU = "Pokaż _MENU_ pozycji"
DATATABLE__SCRIPT_SZERORECORDS = "Nie znaleziono żadnych pasujących indeksów"
DATATABLE__SCRIPT_SINFO = "Pozycje od _START_ do _END_ z _TOTAL_ łącznie"
DATATABLE__SCRIPT_SINFOEMPTY = "Pozycji 0 z 0 dostępnych"
DATATABLE__SCRIPT_SINFOFILTERED = "(filtrowanie spośród _MAX_ dostępnych pozycji)"
DATATABLE__SCRIPT_SSEARCH = "Szukaj:"
DATATABLE__SCRIPT_OPAGINATE_SFIRST = "Pierwsza"
DATATABLE__SCRIPT_OPAGINATE_SPREVIOUS = "Poprzednia"
DATATABLE__SCRIPT_OPAGINATE_SNEXT = "Następna"
DATATABLE__SCRIPT_OPAGINATE_SLAST = "Ostatnia"

[LOGIN]
LOGIN__EROR_MISSING_USERNAME_AND_PASSWORD = "Podaj identyfikator oraz hasło"
LOGIN__ERROR_WRONG_USERNAME = "Identyfikator nieprawidłowy"
LOGIN__ERROR_PASSWORD_LOCK = "Trzykrotnie źle wprowadzono hasło, konto zablokowane.Odczekaj %s sekund przed kolejną próbą logowania."
LOGIN__ERROR_BAD_PASSWORD = "Złe hasło"
LOGIN__ERROR_ACCESS_DENIED_FROM_IP = "Nie masz dostępu do panelu zarządzania z aktualnego adresu IP"
LOGIN__ERROR_ACCOUNT_DISABLED = "Twoje konto jest zablokowane"
LOGIN__ERROR_ACL_MISSING = "Nie masz wystarczających uprawnień do panelu zarządzania"
LOGIN__BOX_TITLE = "Panel Administracyjny"
LOGIN__FIELD_USERNAME = "Identyfikator"
LOGIN__FIELD_PASSWORD = "Hasło"
LOGIN__BUTTON_LOGIN = "Login"

[CORE_DELETED]
CORE_DELETED__FIELD_TABLE = "Tabela"
CORE_DELETED__FIELD_ID = "Identyfikator"
CORE_DELETED__FIELD_DELETED_DATE = "Data usunięcia"
CORE_DELETED__DELETED_OBJECT = "Usunięty objekt"
CORE_DELETED__FIELD_DATA = "Dane"

[CORE_CONFIGADMINVIEW]
CORE_CONFIGADMINVIEW__FIELD_TAG = "Widok"
CORE_CONFIGADMINVIEW__FIELD_DBNAME = "Tabela z danymi"
CORE_CONFIGADMINVIEW__FIELD_MAINKEY = "Podstawowy klucz"
CORE_CONFIGADMINVIEW__FIELD_FUNCTION = "Funkcja do pobierania danych"
CORE_CONFIGADMINVIEW__FIELD_BUTTON_BACK = "Czy przycisk 'wróć' widoczny?"
CORE_CONFIGADMINVIEW__FIELD_BUTTON_ADDNEW = "Czy przycisk 'nowy rekord' widoczny?"
CORE_CONFIGADMINVIEW__FIELD_ROWPERPAGE = "Domyślna ilość wierszy"
CORE_CONFIGADMINVIEW__FIELD_VIEW_FIELDS = "Kolumny widoku"
CORE_CONFIGADMINVIEW__FIELD_VIEW_TITLE = "Tytuł"
CORE_CONFIGADMINVIEW__FIELD_VIEW_WIDTH = "Szerokość"
CORE_CONFIGADMINVIEW__FIELD_VIEW_VALUE = "Zawartość"
CORE_CONFIGADMINVIEW__FIELD_VIEW_ALIGN = "Justowanie"
CORE_CONFIGADMINVIEW__FIELD_VIEW_ORDER = "Sortowanie"

[CORE_PLUGIN]
CORE_PLUGIN__THEAD_PLUGIN = "Plugin"
CORE_PLUGIN__THEAD_AUTHOR = "Autor"
CORE_PLUGIN__THEAD_VERSION = "Wersja"
CORE_PLUGIN__THEAD_STATUS = "Status"
CORE_PLUGIN__ACTION_DISABLE = "wyłącz"
CORE_PLUGIN__ACTION_ENABLE = "włącz"

[INDEX]
INDEX__SYSINFO = "Informacje o aplikacji"
INDEX__CMS_VERSION = "Wersja"
INDEX__PLUGIN_VERSION = "Plugin %s - wersja %s (%s)"
INDEX__MORE_SYSINFO = "więcej informacji o systemie"

[MENU]
MENU__CONTENT = "Zawartość serwisu"
MENU__CONTENT_PAGES = "Podstrony"
MENU__CONTENT_SECTIONS = "Sekcje"
MENU__CONTENT_TEXT = "Artykuły"
MENU__CONTENT_NEWS = "Wiadomości"
MENU__CONTENT_NEWSGROUP = "Kategorie wiadomości"
MENU__CONTENT_MAILTEMPLATE = "Szablony wiadomości e-mail"
MENU__CONTENT_FILE = "Multmedia"
MENU__CONTENT_CATEGORY = "Kategorie zasobów"
MENU__CONTENT_FILEASSOC = "Załączniki do obiektów"
MENU__CONTENT_FILEPOPUP = "Przeglądarka plików"
MENU__CONTENT_TEMPLATE = "Szablony podstron"
MENU__SEARCH_AJAX = "Wyszukiwarka Ajax"
MENU__CONFIG = "Konfiguracja"
MENU__CONTENT_USER = "Użytkownicy"
MENU__CONTENT_USERGROUP = "Grupy użytkowników"
MENU__CONTENT_HOSTALLOW = "Dostęp z adresów IP"
MENU__CONTENT_ACCESS = "Role zabezpieczeń"
MENU__CONTENT_EXTRA = "Dostosowywanie obiektów"
MENU__CONTENT_CRONTAB = "Operacje automatyczne"
MENU__CONTENT_FILESHOWTYPE = "Sposób wyświetlania grafiki"
MENU__CORE_TRANSLATION = "Tłumaczenia"
MENU__PLUGIN = "Dodatki"
MENU__BACKUP = "Kopia zapasowa"
MENU__CORE_CONFIG_ADMIN_VIEW = "Widok panelu zarządzania"
MENU__CORE_CHANGED = "Bufor zmian"
MENU__CORE_TASK = "Zadania"
MENU__SYSINFO = "Informacje o aplikacji"
MENU__LOGIN = "Logowanie"

[SYSINFO]
SYSINFO__ERROR = "brak"
SYSINFO__STATUS_OK = "ok"
SYSINFO__SECTION_MAIN_INFO = "Informacje o aplikacji"
SYSINFO__FIELD_ELEMENT = "Element"
SYSINFO__FIELD_VERSION = "Wersja"
SYSINFO__FIELD_CORE = "Wersja SiteManager"
SYSINFO__PLUGIN = "Plugin"
SYSINFO__PLUGIN_AUTHOR = "Autor"
SYSINFO__SECTION_LIBRARY = "Biblioteki"
SYSINFO__LIBRARY = "Biblioteka"
SYSINFO__FIELD_STATUS = "Stan"
SYSINFO__FIELD_PHP = "Kompilator PHP"
SYSINFO__FIELD_PHP_ERROR_INSTALLED_VERSION = "zainstalowana wersja %s, wymagana wersja >= %s"
SYSINFO__FIELD_MYSQL_SERVER = "Obsługa bazy danych MySQL"

[HEADER]
HEADER__LOGED_USER = "Zalogowany"

[TXT_ALIGN]
TXT__ALIGN_NONE = "brak"
TXT__ALIGN_LEFT = "do lewej"
TXT__ALIGN_CENTER = "po środku"
TXT__ALIGN_RIGHT = "do prawej"
TXT__RECORD_DELETE_CONFIRM = "Czy jesteś pewien, że chcesz usunąć ten rekord?"

[RECORD_HISTORY]
RECORD_HISTORY__TITLE = "Historia rekordu"
RECORD_HISTORY__CREATED = "Rekord utworzono"
RECORD_HISTORY__BY = "przez"
RECORD_HISTORY__MODIFIED = "Zmieniano"

*/
?>