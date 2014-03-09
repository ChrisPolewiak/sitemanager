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
BUTTON__LOGIN = "Login"
BUTTON__LOGOUT = "wyloguj się"
BUTTON__MARSHAL = "uporządkuj dane"
BUTTON__CLOSE = "Zamknij"
BUTTON__SEARCH = "Szukaj"

[SYSINFO]
SYSINFO__MAIN_FIELDSET_TITLE = "Informacje o aplikacji"
SYSINFO__PLUGIN = "Rozszerzenie"
SYSINFO__VERSION = "Wersja"
SYSINFO__PLUGIN_NAME = "Nazwa rozszerzenia"
SYSINFO__PLUGIN_AUTHOR = "Autor"
SYSINFO__LIBRARY_FIELDSET_TTTLE = "Biblioteki"
SYSINFO__LIBRARY_NAME = "Biblioteka"
SYSINFO__STATUS = "Stan"
SYSINFO__STATUS_OK = "ok"
SYSINFO__ERROR = "brak"
SYSINFO__ERROR_INSTALLED_VERSION = "zainstalowana wersja %s, wymagana wersja >= %s"
SYSINFO__INSTALL_CMD = "Procedura instalacji"
SYSINFO__FIELD_MYSQL_SERVER = "Obsługa bazy danych MySQL"

[SEARCH]
SEARCH__FIELDSET_TITLE = "Wyszukiwanie"
SEARCH__ERROR_NOT_FOUND = "Nie zdefiniowano wyszukiwarki dla obiektu"
SEARCH__ERROR_MIN_CHARS_REQUIREMENT = "Należy podać minimum 1 znak"
SEARCH__RESULT_FOUND = "Ilość znalezionych rekordów";
SEARCH__ERROR_NOT_FOUND = "Nic nie znaleziono|Spróbuj inaczej sprecyzować swoje zapytanie..."
SEARCH__ERROR_NOT_DEFINED = "Nie zdefiniowano wyszukiwarki dla obiektu"

[FORM]
FORM__SECURITY_ERROR = "Bład zabezpieczeń formularza"

[LOGIN]
LOGIN__ERROR_MISSING_USERNAME_AND_PASSWORD = "Podaj identyfikator oraz hasło"
LOGIN__ERROR_WRONG_USERNAME = "Identyfikator nieprawidłowy"
LOGIN__ERROR_PASSWORD_LOCK = "Trzykrotnie źle wprowadzono hasło, konto zablokowane.Odczekaj %s sekund przed kolejną próbą logowania."
LOGIN__ERROR_BAD_PASSWORD = "Złe hasło"
LOGIN__ERROR_ACCESS_DENIED_FROM_IP = "Nie masz dostępu do panelu zarządzania z aktualnego adresu IP"
LOGIN__ERROR_ACCOUNT_DISABLED = "Twoje konto jest zablokowane"
LOGIN__ERROR_ACL_MISSING = "Nie masz wystarczających uprawnień do panelu zarządzania"
LOGIN__TITLE = "Logowanie"
LOGIN__FIELD_USERNAME = "Identyfikator"
LOGIN__FIELD_PASSWORD = "Hasło"

[INSTALL]
INSTALL__STEP_START = "Start"
INSTALL__STEP_DB = "Baza danych"
INSTALL__STEP_APP = "Dane aplikacji"
INSTALL__STEP_ACCESS = "Dostęp"
INSTALL__STEP_CONFIRM = "Potwierdź"
INSTALL__STEP_FINISH = "Koniec"
INSTALL__ERROR_MISSING_DBENGINE = "Podaj typ serwera baz danych"
INSTALL__ERROR_MISSING_DBSERVER = "Podaj adres serwera baz danych"
INSTALL__ERROR_MISSING_DBUSER = "Podaj nazwę użytkownika do bazy danych"
INSTALL__ERROR_MISSING_DBPASS = "Podaj hasło dla użytkownika do bazy danych"
INSTALL__ERROR_MISSING_DBPREFIX = "Podaj prefix dla tabel"
INSTALL__ERROR_WRONG_DBPREFIX = "Prefix może składać się wyłacznie z liter i cyfr mi mieć od 2 do 5 znaków"
INSTALL__ERROR_FOUND_DBDUPLICATE = "W bazie znajduje się już instancja SiteManager. Zmień prefix dla tabel, by nie nadpisać istniejących danych."
INSTALL__ERROR_MISSING_CACHETTL = "Podaj domyślny czas przechowywania zdjęć w cache"
INSTALL__ERROR_ADMINPANEL_TOO_SHORT = "Za krótki adres panelu zarządzania"
INSTALL__ERROR_ADMIN_USERNAME_TOO_SHORT = "Identyfikat administratora musi zawierać min 5 znaków"
INSTALL__ERROR_ADMIN_PASSWORD_WRONG = "Hasło musi spełniać wymogi złożoności. Musi zawierać minimum 5 znaków i składać się z cyfr, liter małych oraz dużych"



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
MENU__CORE_SESSION = "Sesje"
MENU__PLUGIN = "Dodatki"
MENU__BACKUP = "Kopia zapasowa"
MENU__CORE_CONFIG_ADMIN_VIEW = "Widok panelu zarządzania"
MENU__CORE_CHANGED = "Bufor zmian"
MENU__CORE_TASK = "Zadania"
MENU__SYSINFO = "Informacje o aplikacji"
MENU__LOGIN = "Logowanie"

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