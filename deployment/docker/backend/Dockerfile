FROM php:8.2-apache

# install utilities
RUN apt update \
    && apt install -y --no-install-recommends curl zip unzip locales apt-utils vim git libicu-dev software-properties-common;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen

# install composer
RUN curl -sSk https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install ext
RUN docker-php-ext-install iconv pdo_mysql intl gd

# config apache
RUN a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo 'alias ll="ls -la"' >> ~/.bashrc

USER 1000:1000

WORKDIR /var/www/html
