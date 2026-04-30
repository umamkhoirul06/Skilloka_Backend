FROM php:8.2-fpm-alpine

# Install dependencies sistem (TERMASUK postgresql-dev)
RUN apk add --no-cache \
    nginx nodejs npm curl zip unzip git supervisor libpng-dev libzip-dev oniguruma-dev libxml2-dev postgresql-dev

# Install ekstensi PHP (Ubah pdo_mysql jadi pdo_pgsql)
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd opcache zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]