Przewidywany czas realizacji: 3 godziny.

Wymagane technologie:

- Zend Framework 1 w wersji dostosowanej do PHP 8.1 (https://github.com/Shardj/zf1-future)
- Bootstrap 5 (https://getbootstrap.com/)

Zadanie:
Na podstawie załączonej bazy danych stwórz raport historii cen lokalu.

Wszystkie zapytania wykonane w ramach realizacji zadania należy przesłać w jednym pliku.

------



Podczas wykonywania zadania można dodawać nowe tabele, triggery oraz widoki, jednak obecnych nie można modyfikować.

Zadania do realizacji:

1. Stworzenie nowej zakładki w menu oraz nowego Controllera
2. Stworzenie raportu wraz z widokiem i filtrami na podstawie poniższych założeń: 

	Raport powinien zawierać następujące kolumny:
	L.p. - liczba porządkowa
	Status - aktualny status lokalu
	Data zmiany ceny - dokładna data i godzina zmiana ceny w lokalu
	Inwestycja - nazwa inwestycji przypisanej do lokalu w którym zmieniono cenę
	Typ lokalu - typ lokalu w którym zmieniono cenę
	Numer lokalu - numer lokalu w którym zmieniono cenę
	Poprzednia cena - cena katalogowa lokal przez zmianą
	Nowa cena - cena katalogowa lokal po zmianie
	Poprzednia cena za m2 - cena katalogowa za m2 lokalu przed zmianą
	Nowa cena za m2 - cena katalogowa za m2 lokalu po zmianą

	W raporcie powinny być dostępne następujące filtry:
	Numer lokalu - pole tekstowe po którym system będzie wyszukiwał nazwy lokalu
	Inwestycja - pole wielokrotnego wyboru do filtrowania wyników po wybranych inwestycjach
	Status - pole wielokrotnego wyboru do filtrowania wyników po wybranych aktualnych statusach lokali
	Typ lokalu - pole wielokrotnego wyboru do filtrowania wyników po wybranych typach lokali
	Data zmiany ceny od i do - dwa pola do wybrania zakresu dat od i do aby pokazać wyniki tylko z tego okresu.
