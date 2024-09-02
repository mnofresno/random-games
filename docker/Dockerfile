FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    unzip \
    oniguruma-dev \
    zlib-dev \
    libxml2-dev \
    && docker-php-ext-install zip pdo pdo_mysql mysqli mbstring xml opcache

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html/logs /var/www/html/public/games && chmod -R 777 /var/www/html/logs /var/www/html/public/games
RUN mkdir -p /run/nginx /var/log/nginx

COPY ./docker/nginx/default.conf /etc/nginx/http.d/default.conf

RUN mkdir -p /run/nginx

COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

COPY ./src /var/www/html/src
COPY ./public /var/www/html/public
COPY ./composer.json /var/www/html/
COPY ./composer.lock /var/www/html/
COPY ./instructions_prompt.md /var/www/html/
RUN composer install

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
