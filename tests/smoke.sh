#!/usr/bin/env bash
set -euo pipefail

BASE_URL="http://localhost:${APP_PORT_MAP:-8075}"

echo "[SMOKE] GET /"
curl -fsS "${BASE_URL}/" | grep -qi "Lista inwestycji" && echo "OK: /"

echo "[SMOKE] GET /price-history"
curl -fsS "${BASE_URL}/price-history" | grep -qi "Historia cen" && echo "OK: /price-history"

echo "[SMOKE] GET /status-report"
curl -fsS "${BASE_URL}/status-report" | grep -qi "Raport statusów" && echo "OK: /status-report"

echo "[SMOKE] Filtry price-history (bez błędu 500)"
curl -fsS "${BASE_URL}/price-history?number=1&date_from=2020-01-01&date_to=2030-12-31" >/dev/null && echo "OK: filters"

echo "[SMOKE] DONE"