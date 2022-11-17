FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libfreetype6 \
        zlib1g-dev \
        git-core

RUN apt-get install -y  --no-install-recommends \
               libzip-dev \
               libpq-dev \
               libfreetype6-dev \
               libicu-dev \
               libssl-dev \
               libmcrypt-dev \
               zip \
               unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql zip sockets intl exif \
    && docker-php-ext-enable mysqli pdo pdo_mysql sockets intl exif \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
