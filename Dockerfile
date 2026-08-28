FROM php:8.3-fpm-alpine AS builder

# ---------- System packages & PHP extensions ----------
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_mysql \
        zip \
        intl \
        bcmath \
        gd \
    && docker-php-ext-enable opcache

# ---------- Composer ----------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------- Node.js & npm ----------
RUN apk add --no-cache nodejs npm

WORKDIR /app

# ---------- PHP dependencies ----------
COPY composer.json composer.lock artisan ./

# Install Composer dependencies without Laravel scripts
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --prefer-dist \
    --no-scripts

# ---------- Front-end dependencies ----------
COPY package*.json ./

RUN npm ci

# ---------- Application code ----------
COPY . .

# ---------- Build frontend ----------
RUN npm run build


# ============================================================
# Runtime image
# ============================================================

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# ---------- Runtime dependencies & PHP extensions ----------
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_mysql \
        zip \
        intl \
        bcmath \
        gd \
    && docker-php-ext-enable opcache

# ---------- Copy application ----------
COPY --from=builder /app /var/www/html

# ---------- Permissions ----------
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
