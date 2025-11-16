FROM php:8.2-fpm

WORKDIR /var/www/backend

# Установка системных пакетов и зависимостей для PHP-расширений
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
    postgresql-client \
    curl \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Конфигурация gd и установка расширений (включая sockets)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        zip \
        gd \
        curl \
        mbstring \
        exif \
        pdo_pgsql \
        pgsql \
        sockets

# Imagick
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /usr/src/php/ext/imagick \
    && chmod 777 /usr/src/php/ext/imagick \
    && curl -fsSL https://github.com/Imagick/imagick/archive/refs/tags/3.7.0.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1 \
    && docker-php-ext-install imagick

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

# Копируем приложение (предполагается, что в контексте есть папка backend)
COPY ./backend ./

# Установка PHP-зависимостей (composer)
RUN composer install --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# .pgpass
RUN echo "postgresql:54321:booking_test:postgres:postgres" > /var/local/.pgpass

# Права
RUN chown -R www-data:www-data storage bootstrap/cache || true
RUN chown -R www-data:www-data /var/local/.pgpass || true

RUN chmod -R 0777 storage bootstrap/cache || true
RUN chmod 0600 /var/local/.pgpass || true

EXPOSE 9000
