#!/bin/bash

composer install

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -f .env.testing ]; then
    cp .env.testing.example .env
fi

dockerize -wait tcp://desafio_laravel.database:3306 -timeout 25s
php artisan key:generate
php artisan migrate --seed
php artisan queue:work --tries 2 >storage/logs/queue.log &

php-fpm