# Use PHP as the base image
FROM php:7.4

# Install necessary packages
RUN apt-get update \
    && apt-get install -y \
        git \
        wget \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP_CodeSniffer for coding standards
RUN composer global require "squizlabs/php_codesniffer=*"

# Install PHPStan for static analysis
RUN composer global require "phpstan/phpstan"

# Install PHP-CS-Fixer for automatic fixing (upgrade to version 3)
RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer \
    && chmod +x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

# Copy custom rules configuration file
COPY php_cs_custom_rules.php /usr/local/bin/php_cs_custom_rules.php

# Set PHP_CS_FIXER_IGNORE_ENV environment variable to prevent .php_cs.dist or .php_cs from being loaded
ENV PHP_CS_FIXER_IGNORE_ENV=1

# Add Composer global bin to PATH
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Set higher PHP memory limit
RUN echo "memory_limit=256M" > /usr/local/etc/php/php.ini

# Set working directory
WORKDIR /app
