# Onboarding — uruchomienie projektu

## Wymagania
- Docker + Docker Compose V2
- Wolne porty: 8075 (app), 3318 (DB)

## Szybki start
1. Sklonuj repozytorium:
   - git clone git@github.com:pszemokoziniak/onboarding.git
   - cd onboarding/docker

2. Sprawdź plik .env (opcjonalnie zmień porty):
   - APP_PORT_MAP=8075
   - DB_PORT_MAP=3318

3. Uruchom środowisko:
   - docker compose up -d

4. Aplikacja:
   - http://localhost:8075

5. Baza danych:
   - Host (z hosta): 127.0.0.1
   - Port: 3318
   - Użytkownik: docker
   - Hasło: docker
   - Baza: onboard

## Inicjalizacja bazy
- Skrypty z katalogu docker/db/scripts są montowane do /docker-entrypoint-initdb.d i wykonują się automatycznie przy pierwszym starcie pustego wolumenu.
- Aby wymusić ponowną inicjalizację (UWAGA: usunięcie danych):
  - docker compose down -v
  - docker compose up -d

## Połączenie z DB z kontenera aplikacji
- docker compose exec app bash
- mysql -h db -P 3306 -u docker -pdocker --ssl=0 onboard

## Struktura
- docker/app/Dockerfile — obraz aplikacji (PHP 8.1 + Apache)
- docker/db/scripts — skrypty inicjalizacji bazy (0_init.sql, 1_price_history.sql)
- application — kod Zend Framework 1 (kontrolery, widoki)
- public_html — DocumentRoot dla Apache

## Testy (SQL + smoke)

### Testy SQL (integracyjne)
Uruchamiają weryfikację tabeli/widoku oraz triggera historii cen.

- Polecenie:
    - cd docker
    - docker compose exec -T app sh -lc 'mysql -h db -P 3306 -u docker -pdocker --ssl=0 onboard < /var/www/html/docker/db/scripts/2_tests.sql'

- Oczekiwane:
    - Istnieje tabela `realestate_price_history` i widok `vw_realestate_price_history`
    - Dwa wpisy historii dla zaktualizowanego lokalu (test podnosi i przywraca cenę)

### Smoke test HTTP
Prosty test końcowy dla głównych stron i filtrów.

- Uruchomienie:
    - ./tests/smoke.sh

- Co sprawdza:
    - Strona główna: “Lista inwestycji”
    - Raport historii cen: “Historia cen”
    - Raport statusów: “Raport statusów”
    - Filtry w historii cen nie zwracają błędu 500

### Uruchomienie całości (SQL + HTTP)
Skrypt odpala testy SQL i smoke z dowolnej lokalizacji.

- Polecenie:
    - ./tests/run_all.sh