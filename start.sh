#!/bin/bash

# Determine database path
if [ -n "$RAILWAY_VOLUME_MOUNT_PATH" ]; then
    DB_PATH="$RAILWAY_VOLUME_MOUNT_PATH/database.sqlite"
    echo "Using Railway volume at: $DB_PATH"
else
    # Create database directory if it doesn't exist
    mkdir -p database
    DB_PATH="database/database.sqlite"
    echo "Using local database at: $DB_PATH"
fi

# Create SQLite database file if it doesn't exist
if [ ! -f "$DB_PATH" ]; then
    touch "$DB_PATH"
    echo "Created new database.sqlite file at $DB_PATH"
fi

# Run migrations
php artisan migrate --force

# Seed database only if products table is empty
php artisan db:seed --class=ProductSeeder --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the server
php -S 0.0.0.0:$PORT -t public
