FROM php:8.1-apache as base

RUN apt-get update && apt-get install -y libbz2-dev \
zlib1g-dev \
libpng-dev \
libjpeg-dev \
libgmp3-dev \
libc-client-dev \
libkrb5-dev \
libxml2-dev \
libmcrypt-dev \
libmemcached-dev \
libzip-dev \
libcurl4-openssl-dev \
libonig-dev \
libedit-dev \
libfreetype6-dev \
iproute2 \
wait-for-it \
dos2unix \
zip \
git \
uuid-runtime \ 
gettext \
bsdmainutils \ 
libssh2-1-dev \
libmagickwand-dev

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql \
    mysqli \
    bcmath \
    bz2 \
    gd \
    gettext \
    pcntl \
    sockets \
    intl \
    zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug



COPY docker/app/config/vhost.conf /etc/apache2/sites-available/onboard.conf

RUN a2dissite 000-default \
    && a2ensite onboard \
    && a2enmod rewrite \
    && rm /etc/apache2/sites-available/000-default.conf

COPY docker/app/scripts/init.sh /usr/local/bin/init.sh
CMD ["/usr/local/bin/init.sh"]

FROM base AS staging
COPY . .
CMD ["/usr/local/bin/init.sh"]
