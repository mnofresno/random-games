FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    unzip \
    oniguruma-dev \
    zlib-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make \
    linux-headers \
    && docker-php-ext-install zip pdo pdo_mysql mysqli mbstring xml opcache

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html/public/games && chmod -R 777 /var/www/html/public/games
RUN mkdir -p /run/nginx /var/log/nginx

RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
RUN touch /var/log/xdebug_remote.log
RUN chown www-data:www-data /var/log/xdebug_remote.log

RUN apk add bash
COPY ./docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY ./docker/host_dns.sh /usr/bin/host_dns.sh
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
