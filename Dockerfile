# ─── Stage 1: Base (PHP + Extensions + Oracle) ────────────────────────────────
FROM php:8.2-apache-bookworm AS base

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libzip-dev libxml2-dev \
    unzip libaio1 curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip mbstring soap \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /opt/oracle
COPY instantclient-basiclite-linux.x64-21.6.0.0.0dbru.zip \
    instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip ./

RUN unzip -q instantclient-basiclite-linux.x64-21.6.0.0.0dbru.zip \
    && unzip -q instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip \
    && rm *.zip \
    && ln -s /opt/oracle/instantclient_21_6 /opt/oracle/instantclient \
    && echo '/opt/oracle/instantclient' > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

ENV ORACLE_HOME=/opt/oracle/instantclient \
    LD_LIBRARY_PATH=/opt/oracle/instantclient \
    TNS_ADMIN=/opt/oracle/instantclient \
    PATH=$PATH:/opt/oracle/instantclient

RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient \
    && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient \
    && docker-php-ext-install oci8 pdo_oci

RUN a2enmod rewrite
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# ─── Stage 2: Vendor (PHP Dependencies) ───────────────────────────────────────
FROM composer:2 AS vendor-builder
WORKDIR /app
COPY composer.json composer.lock ./

# Install all deps including dev (ide-helper must already be in composer.json)
RUN composer install --no-interaction --no-scripts --no-plugins \
    --prefer-dist --ignore-platform-reqs

# ─── Stage 3: Assets (Node) ───────────────────────────────────────────────────
FROM node:21-slim AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline
COPY . .
RUN npm run build

# ─── Stage 4: Development Runtime ─────────────────────────────────────────────
FROM base AS development
COPY --from=vendor-builder /app/vendor ./vendor
COPY --from=node-builder /app/public/build ./public/build
COPY --chown=www-data:www-data . .
RUN chown -R www-data:www-data storage bootstrap && chmod -R 775 storage bootstrap

# Generate ide-helper files here, where the full app + artisan exist

COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# ─── Stage 5: Production Runtime ──────────────────────────────────────────────
FROM base AS production
COPY --from=vendor-builder /app/vendor ./vendor
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-interaction --no-scripts --no-plugins \
    --optimize-autoloader --ignore-platform-reqs \
    && rm /usr/local/bin/composer
COPY --from=node-builder /app/public/build ./public/build
COPY --chown=www-data:www-data . .
RUN chown -R www-data:www-data storage bootstrap && chmod -R 775 storage bootstrap
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]