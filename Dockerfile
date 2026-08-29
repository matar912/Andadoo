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

# Passer temporairement en root pour modifier la configuration système
USER root

WORKDIR /var/www/html

# Copier l'application construite depuis le stage builder
COPY --from=builder --chown=www-data:www-data /app /var/www/html

# Automatiser les migrations et le cache au démarrage du conteneur
RUN echo '#!/bin/sh' > /etc/entrypoint.d/99-laravel-deploy.sh \
    && echo 'php artisan migrate --force' >> /etc/entrypoint.d/99-laravel-deploy.sh \
    && echo 'php artisan config:cache' >> /etc/entrypoint.d/99-laravel-deploy.sh \
    && echo 'php artisan route:cache' >> /etc/entrypoint.d/99-laravel-deploy.sh \
    && echo 'php artisan view:cache' >> /etc/entrypoint.d/99-laravel-deploy.sh \
    && chmod +x /etc/entrypoint.d/99-laravel-deploy.sh

# Revenir à l'utilisateur non-root pour la sécurité
USER www-data
