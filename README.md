# Ankiety
System ankietowy

# Tryb developerski
W plikach index.php i admin.php jest użyta metoda \KPrzemyslaw\KPConfigure::getVersion(true) do pobierania wersji aplikacji, zawierajaca unixowy znacznik czasu, w celu wymuszenia każdorazowego pobierania zasobów JS, CSS i HTML
Do użytku produkcyjnego, należy ten tryb wyłączyć przez \KPrzemyslaw\KPConfigure::getVersion() - brak parametry (domyślne false).

# Plik konfiguracyjny etc/configure.json
Plik zawiera JSON z danymi połaczenia bazy danych i numerem (głównym) wersji.

# Baza danych sql/Dump20180831.sql
Zrzut bazy (szczególnie tu chodzi o strukturę) należy zaimpoertować do serwera MySQL w wersji min. 5.6 lub nowszej

# PHP i środowisko serwerowe
Aplikacja napisana i uruchamiana z wykorzystaniem PHP 7.2.9 i serwera Apache/2.4.18

# Kompilacja JS, CSS, HTML w środowisku Linux
Aplikacja bazuje na kompilowanych Bundlach. Aby zmiany w kodzie zostały użyte przez aplikację należy dokonać kompilacji:
$ sh/app_compiler.sh

# Uprawnienia do plików!
Większość problemów może być spowodowane przez błędne uprawnienia do plików i katalogów na co trzeba zwrócić szczególną uwagę!
