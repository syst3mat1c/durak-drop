FROM php:7.1-fpm

WORKDIR /app

RUN apt-get update --fix-missing

RUN apt-get install -y gnupg apt-utils && \
    apt-get install -y git && \
    apt-get install -y nano && \
    apt-get install -y libmcrypt-dev \
        libpq-dev \
        libpng-dev \
        libxml2-dev \
        libxslt-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libbz2-dev \
        libmagickwand-dev && \
    rm -rf /var/lib/apt/lists/*


RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt-get install -yq nodejs build-essential


RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/  && \
        (yes | pecl install imagick) && \
        docker-php-ext-install \
            mcrypt \
            bcmath \
            mbstring \
            zip \
            opcache \
            pdo_mysql \
            gd \
            xmlrpc \
            opcache \
            intl \
            mysqli \
            bz2 \
            exif

# Mongo install
#RUN pecl install mongodb

COPY ./docker/php/fpm_www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php/docker-php-ext-imagick.ini /usr/local/etc/php/conf.d/docker-php-ext-imagick.ini
COPY ./docker/php/php.ini /usr/local/etc/php/
#ADD ./docker/mysql/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

COPY ./scheduler-nodejs.sh /usr/local/bin/scheduler-nodejs
RUN chmod u+x /usr/local/bin/scheduler-nodejs

COPY . /app
#COPY ./app/.env.example /app/.env

# Register the COMPOSER_HOME environment variable
ENV COMPOSER_HOME /composer
# Add global binary directory to PATH and make sure to re-export it
ENV PATH /composer/vendor/bin:$PATH
# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_VERSION 1.4.2

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }"

# Install Composer
RUN php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} && rm -rf /tmp/composer-setup.php

# Install PHPUnit
RUN curl -L -o /tmp/phpunit.phar  https://phar.phpunit.de/phpunit.phar \
  && mv /tmp/phpunit.phar /usr/local/bin/phpunit \
  && chmod +x /usr/local/bin/phpunit

USER root
