#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"

echo "[RUN] SQL tests"
docker compose -f "$ROOT_DIR/docker/docker-compose.yml" -f "$ROOT_DIR/docker/docker-compose.dev.yml" exec -T app sh -lc 'mysql -h db -P 3306 -u docker -pdocker --ssl=0 onboard < /var/www/html/docker/db/scripts/2_tests.sql'

echo "[RUN] HTTP smoke"
"$ROOT_DIR/tests/smoke.sh"

echo "[RUN] ALL OK"