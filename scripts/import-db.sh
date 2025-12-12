#!/usr/bin/env bash
set -euo pipefail

# Usage: ./scripts/import-db.sh database/legends.sql
# Requires mysql client to be installed and DB env vars set (DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD, DB_DATABASE)

SQL_FILE=${1:-database/legends.sql}

if [ ! -f "$SQL_FILE" ]; then
  echo "SQL file not found: $SQL_FILE"
  exit 1
fi

if [ -z "${DB_HOST:-}" ] || [ -z "${DB_PORT:-}" ] || [ -z "${DB_USERNAME:-}" ] || [ -z "${DB_DATABASE:-}" ]; then
  echo "Please set DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD, and DB_DATABASE environment variables."
  exit 1
fi

echo "Importing $SQL_FILE into $DB_DATABASE@$DB_HOST:$DB_PORT"

# -p"$DB_PASSWORD" is used to avoid prompting; if DB_PASSWORD is empty, omit -p
if [ -z "${DB_PASSWORD:-}" ]; then
  mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME $DB_DATABASE < $SQL_FILE
else
  mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p"$DB_PASSWORD" $DB_DATABASE < $SQL_FILE
fi

echo "Done."
