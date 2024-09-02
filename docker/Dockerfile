FROM php:7.4-apache
RUN apt-get update && apt-get install -y libzip-dev unzip && docker-php-ext-install zip
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer install
RUN chmod -R 777 /var/www/html/logs /var/www/html/public/games
EXPOSE 80
CMD ["apache2-foreground"]
