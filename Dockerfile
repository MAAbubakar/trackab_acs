FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libxml2-dev libonig-dev libicu-dev libxslt1-dev \
    nodejs npm default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring xml zip bcmath gd intl soap dom \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN git config --global --add safe.directory /var/www/html

COPY . .

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
