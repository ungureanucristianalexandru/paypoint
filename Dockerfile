FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    libssl-dev \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd opcache pdo pdo_mysql intl zip xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-scripts --no-autoloader

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/

EXPOSE 80

CMD service nginx start && php-fpm
