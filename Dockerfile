FROM dunglas/frankenphp:php8.4.4-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /php/laravel

RUN apk update && \
    apk add \
    $PHPIZE_DEPS \
    nodejs npm \
    unzip \
    zlib-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    coreutils \
    git \
    openssh-client

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install \
    gd \
    zip \
    pdo_mysql \
    mbstring \
    pcntl \
    bcmath \
    opcache

COPY . .

RUN composer install

RUN npm install

RUN npm run build

ENTRYPOINT [ "tail", "-f", "/dev/null" ]