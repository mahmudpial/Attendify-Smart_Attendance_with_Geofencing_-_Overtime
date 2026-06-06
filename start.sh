#!/bin/bash

# Run database migrations
php artisan migrate --force

# Start supervisor (which runs nginx and php-fpm)
/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf