FROM php:7.4-fpm
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip 
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/app

ADD composer.json composer.json

RUN composer update

VOLUME /var/www/html