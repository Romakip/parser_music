FROM ubuntu:20.04

WORKDIR /var/www/music-app

ENV LANG=ru_RU.UTF-8

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get -y install php7.3 libapache2-mod-php7.3 \
    && apt-get -y install php7.3-xml \
    php7.3-mysql \
    php7.3-gd \
    php7.3-mbstring \
    php7.3-intl \
    php7.3-curl \
    php7.3-pgsql

COPY . .

COPY --from=composer:2.2.7 /usr/bin/composer /usr/local/bin/composer

RUN composer install
