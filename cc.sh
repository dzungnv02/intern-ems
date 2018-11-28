#!/bin/bash
echo "php artisan cache:clear" & php artisan cache:clear
echo "php artisan view:cache" & php artisan view:cache
echo "php artisan config:cache" & php artisan config:cache
echo "composer dump-autoload" & composer dump-autoload