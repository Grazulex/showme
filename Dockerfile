# Dockerfile optimisé pour Laravel 12 avec PHP 8.4
FROM php:8.4-fpm-alpine

# Arguments pour l'utilisateur
ARG USER_ID=1000
ARG GROUP_ID=1000

# Installation des dépendances système nécessaires
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    postgresql-dev \
    postgresql-client \
    sqlite \
    sqlite-dev \
    nginx \
    netcat-openbsd

# Installation des extensions PHP requises par Laravel 12
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Installation de l'extension Redis via PECL
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Modifier www-data pour avoir le même UID que l'hôte (pour éviter les conflits de permissions)
RUN deluser www-data; delgroup www-data; \
    addgroup -g ${GROUP_ID} www-data; \
    adduser -D -u ${USER_ID} -G www-data www-data

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de configuration PHP et Nginx AVANT le code
COPY --chown=www-data:www-data docker/php/conf.d/laravel.ini /usr/local/etc/php/conf.d/
COPY --chown=www-data:www-data docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/
COPY --chown=www-data:www-data docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=www-data:www-data docker/nginx/conf.d/default.conf /etc/nginx/http.d/default.conf

# Copier le script d'entrée
COPY --chown=www-data:www-data docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Créer les répertoires nécessaires avec les bonnes permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Exposition des ports (80 pour Nginx, 9000 pour PHP-FPM)
EXPOSE 80 9000

# Point d'entrée
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Commande par défaut (démarrage de PHP-FPM et Nginx)
CMD ["php-fpm"]
