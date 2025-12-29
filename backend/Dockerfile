FROM php:8.2-cli

# Install MySQL PDO driver
RUN docker-php-ext-install pdo_mysql

WORKDIR /app
COPY . /app

# Railway provides $PORT
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public index.php"]
