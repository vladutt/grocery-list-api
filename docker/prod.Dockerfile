FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

ARG USER=www-data
ENV PHP_VERSION=8.3

RUN apt update
RUN apt install lsb-release ca-certificates apt-transport-https software-properties-common -y
RUN add-apt-repository ppa:ondrej/php

RUN apt-get install -y --no-install-recommends \
    curl zip unzip mc nginx supervisor \
    zlib1g-dev libpng-dev libicu-dev libonig-dev libxml2-dev libzip-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists*

RUN apt-get update && apt-get install -y libyaml-dev nano wget

# install php extensions
RUN apt-get update && apt-get -y install php${PHP_VERSION}-cli php${PHP_VERSION}-fpm php${PHP_VERSION}-xml php${PHP_VERSION}-dev \
    php${PHP_VERSION}-intl php${PHP_VERSION}-mbstring php${PHP_VERSION}-mysql php${PHP_VERSION}-curl \
    php${PHP_VERSION}-opcache php${PHP_VERSION}-zip php${PHP_VERSION}-exif php${PHP_VERSION}-dom php${PHP_VERSION}-yaml php${PHP_VERSION}-xdebug

RUN chown -R nobody:nogroup /run

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN chmod o+w ./storage/ -R

RUN service php${PHP_VERSION}-fpm start

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
