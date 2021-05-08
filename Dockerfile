FROM php:8.0-fpm-alpine

WORKDIR /var/www/html

COPY . .
COPY docker/php/php.ini /usr/local/etc/php/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install mysqli pdo_mysql

RUN apk add --no-cache bash git fish

# Dockerize
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN chmod +x ./docker/php/entrypoint.sh

RUN apk add --no-cache shadow
RUN usermod -u 1000 www-data
USER www-data

EXPOSE 9000
ENTRYPOINT [ "docker/php/entrypoint.sh" ]