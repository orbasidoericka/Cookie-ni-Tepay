#!/bin/bash

# Create database directory if it doesn't exist
mkdir -p database

# Create SQLite database file if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "Created new database.sqlite file"
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
