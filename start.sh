#!/bin/bash

echo "Starting Buttercloud Bakery..."
echo "Database connection: $DB_CONNECTION"
echo "Database host: $DB_HOST"

# Clear any cached config first
php artisan config:clear || true

# Wait for DB to be ready (if using mysql)
if [ "${DB_CONNECTION:-}" = "mysql" ]; then
	MAX_ATTEMPTS=30
	ATTEMPT=0
	echo "Waiting for MySQL database to be available..."
	until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'OK'; } catch(Exception \$e) { exit(1); }" 2>/dev/null; do
		ATTEMPT=$((ATTEMPT+1))
		if [ $ATTEMPT -ge $MAX_ATTEMPTS ]; then
			echo "Timed out waiting for DB after $MAX_ATTEMPTS attempts. Continuing..."
			break
		fi
		echo "DB not ready; check Railway MySQL plugin. Attempt $ATTEMPT/$MAX_ATTEMPTS..."
		sleep 2
	done
fi

# Run migrations (continue even if fails)
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed or already done"

# Seed database only if products table is empty
echo "Checking if seeding is needed..."
php artisan db:seed --class=ProductSeeder --force || echo "Seeding failed or already done"

# Cache configuration
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start the server
echo "Starting server on port $PORT..."
php -S 0.0.0.0:$PORT -t public
