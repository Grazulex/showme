#!/bin/sh
set -e

echo "----------------------------------------"
echo "Entrypoint Laravel 12 Docker Container"
echo "----------------------------------------"

# Attendre que PostgreSQL soit prêt
if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for PostgreSQL to be ready..."
    while ! nc -z $DB_HOST ${DB_PORT:-5432}; do
        sleep 1
    done
    echo "PostgreSQL is ready!"
fi

# Attendre que Redis soit prêt
if [ "$CACHE_STORE" = "redis" ] || [ "$QUEUE_CONNECTION" = "redis" ]; then
    echo "Waiting for Redis to be ready..."
    while ! nc -z $REDIS_HOST ${REDIS_PORT:-6379}; do
        sleep 1
    done
    echo "Redis is ready!"
fi

# Créer les répertoires nécessaires s'ils n'existent pas
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Corriger la propriété et les permissions des répertoires critiques
# Cela résout les problèmes de permissions liés aux volumes montés depuis l'hôte
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# S'assurer que le répertoire vendor a aussi les bonnes permissions si existant
[ -d "vendor" ] && chown -R www-data:www-data vendor

# Installer les dépendances Composer si vendor/ n'existe pas
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Générer la clé de l'application si elle n'existe pas
if [ ! -f ".env" ] || ! grep -q "APP_KEY=" .env || [ -z "$(grep APP_KEY= .env | cut -d '=' -f2)" ]; then
    echo "Generating application key..."
    php artisan key:generate --ansi
fi

# Créer la base de données SQLite si nécessaire
if [ "$DB_CONNECTION" = "sqlite" ] && [ ! -f "database/database.sqlite" ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
fi

# Exécuter les migrations (avec --force pour production)
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Vider et recréer le cache
echo "Optimizing application..."
php artisan config:cache
# php artisan route:cache  # Désactivé temporairement - TypeError avec Laravel 12
php artisan view:cache

# Lier le storage public
php artisan storage:link

# Démarrer PHP-FPM en arrière-plan
echo "Starting PHP-FPM..."
php-fpm -D

# Démarrer Nginx en premier plan
echo "Starting Nginx..."
nginx -g 'daemon off;'
