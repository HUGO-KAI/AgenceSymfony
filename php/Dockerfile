FROM php:7.4-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libzip-dev libonig-dev libxslt-dev;
RUN echo "en_US.UTF-8 UTF-8" > /etc/local.gen && \
    echo "fr_FR.UTF-8 UTF-8">>/etc/local.gen && \
    locale-gen

RUN curl -s https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu
RUN composer install


WORKDIR /var/www/