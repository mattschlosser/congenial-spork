# syntax=docker/dockerfile:1
FROM composer:2 AS build

WORKDIR /app/
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

FROM php:8.3-alpine

# recommended: install optional extensions ext-ev and ext-sockets
RUN apk --no-cache add ${PHPIZE_DEPS} linux-headers libev \ 
    && pecl install ev \
    && docker-php-ext-enable ev \
    && docker-php-ext-install sockets \
    && apk del ${PHPIZE_DEPS}
    
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app/
COPY public/ public/
COPY src/ src/
COPY --from=build /app/vendor/ vendor/

ENV X_LISTEN 0.0.0.0:8080
EXPOSE 8080

USER nobody:nobody
ENTRYPOINT ["php", "public/index.php"]
