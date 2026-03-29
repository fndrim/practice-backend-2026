FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip git curl
RUN docker-php-ext-install pdo_mysql bcmath gd zip

WORKDIR /var/www
COPY . .

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer