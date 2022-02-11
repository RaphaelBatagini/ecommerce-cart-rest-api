FROM php:7.4-apache

#install some base extensions
RUN apt-get update
RUN apt-get install -y \
        libzip-dev \
        zip
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip && a2enmod rewrite

RUN pecl install grpc \
    && docker-php-ext-enable grpc