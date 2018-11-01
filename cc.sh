#!/bin/bash
composer dump-autoload & php artisan cache:clear & php artisan view:cache & php artisan config:cache
