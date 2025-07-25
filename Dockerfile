FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    mysql-client \
    git \
    curl \
    autoconf \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    freetype-dev \
    icu-dev \
    shadow \
    oniguruma-dev \
    gmp-dev \
    imagemagick-dev \
    supervisor \
    libjpeg-turbo \
    libpng \
    libwebp \
    freetype \
    icu \
    gmp \
    libzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        gd \
        exif \
        pcntl \
        bcmath \
        opcache \
        intl \
        gmp \
        mbstring

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN apk del --no-cache \
    autoconf \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    gmp-dev \
    imagemagick-dev \
    && rm -rf /tmp/* /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
