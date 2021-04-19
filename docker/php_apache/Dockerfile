FROM php:7.4-apache

# install utilities
RUN apt-get update \
    && apt-get install -y --no-install-recommends curl zip unzip locales apt-utils vim git libicu-dev software-properties-common;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# install composer
RUN curl -sSk https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install ext !!! todo virer en prod !!!
RUN docker-php-ext-install pdo_mysql intl \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p /var/www/html/var \
    && chown -R $(id -u):$(id -g) /var/www/html/var \
    && chmod -R 775 /var/www/html/var

# config apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && echo 'alias ll="ls -la"' >> ~/.bashrc

WORKDIR /var/www/html
