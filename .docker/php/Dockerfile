FROM php:8.2-fpm

WORKDIR /var/www/backend

RUN apt-get update && apt-get install -y \
    git \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    wget \
    --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_pgsql -j$(nproc) gd curl mbstring exif

RUN apt-get update \
    && apt-get install -y libmagickwand-dev --no-install-recommends \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /usr/src/php/ext/imagick \
    && chmod 777 /usr/src/php/ext/imagick \
    && curl -fsSL https://github.com/Imagick/imagick/archive/refs/tags/3.7.0.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1 \
    && docker-php-ext-install imagick \
    && rm /etc/ImageMagick-6/policy.xml

RUN apt-get update && apt-get install -y curl && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo_pgsql pgsql

RUN apt-get install -y postgresql-client

COPY ./backend ./

RUN composer install --optimize-autoloader --no-scripts

RUN echo "postgresql:54321:booking_test:postgres:postgres" > /var/local/.pgpass

RUN chown -R $USER:www-data storage
RUN chown -R $USER:www-data bootstrap/cache
RUN chown -R $USER:www-data /var/local/.pgpass

RUN chmod -R 777 storage
RUN chmod -R 777 bootstrap/cache
RUN chmod 0600 /var/local/.pgpass

EXPOSE 9000
