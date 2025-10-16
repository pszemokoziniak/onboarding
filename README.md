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