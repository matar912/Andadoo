# ============================================================
# Stage 1: Builder (Installation & Compilation Assets)
# ============================================================
FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache \
    git curl libpng-dev libzip-dev zip unzip oniguruma-dev icu-dev postgresql-dev \
    autoconf gcc g++ make nodejs npm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_pgsql pdo_mysql zip intl bcmath gd \
    && docker-php-ext-enable opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock artisan ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ============================================================
# Stage 2: Runtime (Nginx + PHP-FPM prêts pour la production)
# ============================================================
FROM serversideup/php:8.3-fpm-nginx

WORKDIR /var/www/html

# Copier l'application construite depuis le stage builder
COPY --from=builder --chown=www-data:www-data /app /var/www/html
