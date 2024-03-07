#!/bin/bash

chown -R 1000:www-data storage/ bootstrap/cache/
chmod -R 775 storage/ bootstrap/cache/

echo "Starting PHP server..."
php-fpm
