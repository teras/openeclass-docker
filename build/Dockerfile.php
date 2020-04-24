FROM php:fpm

ARG mversion=3.7
ARG version=3.7.1

RUN buildDeps=" \
        default-libmysqlclient-dev \
        libbz2-dev \
        libmemcached-dev \
        libsasl2-dev \
    " \
    runtimeDeps=" \
        curl \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libmemcachedutil2 \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
    " \ 
    && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y $buildDeps $runtimeDeps 

# RUN docker-php-ext-install iconv intl mbstring mysqli opcache pdo_mysql pdo_pgsql  zip gd ldap
RUN docker-php-ext-install iconv intl mysqli opcache pdo_mysql pdo_pgsql  zip gd ldap
