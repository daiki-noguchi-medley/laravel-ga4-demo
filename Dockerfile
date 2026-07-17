# ---- Stage 1: フロント資産(Vite)をビルド ----
FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- Stage 2: PHP / Laravel ----
FROM php:8.4-cli-bookworm

# 必要な拡張
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libsqlite3-dev unzip \
    && docker-php-ext-install zip pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 依存を先に入れてレイヤキャッシュを効かせる
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# アプリ本体
COPY . .
# Stage1 でビルドした public/build を取り込む
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
