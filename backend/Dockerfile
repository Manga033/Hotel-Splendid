FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

FROM php:8.2-cli
RUN docker-php-ext-install pdo_mysql

WORKDIR /app
COPY --from=deps /app/vendor /app/vendor
COPY . /app

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public index.php"]
