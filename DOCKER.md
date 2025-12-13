# Documentation Docker - ShowMe (Laravel 12)

## Architecture de l'environnement

L'environnement Docker est composé de 6 services principaux :

### Services disponibles

1. **app** - Application Laravel avec PHP 8.4-FPM + Nginx
   - Port : 8000 (configurable via `APP_PORT`)
   - Contient toute l'application Laravel
   - Nginx sert les fichiers statiques et proxy vers PHP-FPM

2. **mysql** - Base de données MySQL 8.0
   - Port : 3306 (configurable via `DB_PORT_EXTERNAL`)
   - Base de données : showme
   - Utilisateur : showme / secret
   - Volume persistant : `mysql-data`

3. **redis** - Cache et gestionnaire de queue
   - Port : 6379 (configurable via `REDIS_PORT_EXTERNAL`)
   - Utilisé pour le cache et les queues
   - Volume persistant : `redis-data`

4. **vite** - Serveur de développement Node.js avec HMR
   - Port : 5173 (configurable via `VITE_PORT`)
   - Hot Module Replacement activé
   - Rebuild automatique des assets

5. **queue** - Worker Laravel pour les queues
   - Traite les jobs en arrière-plan
   - Redémarre automatiquement en cas d'erreur

6. **mailpit** - Capture des emails en développement
   - Interface Web : 8025 (configurable via `MAILPIT_UI_PORT`)
   - SMTP : 1025 (configurable via `MAILPIT_SMTP_PORT`)
   - Accessible sur http://localhost:8025

## Prérequis

- Docker Engine 20.10+
- Docker Compose 2.0+
- Stackmark CLI (optionnel mais recommandé)
- 4 GB RAM minimum disponible pour Docker

## Installation et démarrage

### Méthode 1 : Avec Stackmark (recommandé)

1. **Copier le fichier de configuration**
   ```bash
   cp .env.docker .env
   ```

2. **Adapter les variables si nécessaire**
   Editez le fichier `.env` pour ajuster :
   - Les ports externes
   - Les identifiants de base de données
   - Votre USER_ID et GROUP_ID (important pour les permissions)

3. **Enregistrer le stack dans Stackmark**
   ```bash
   stackmark add showme
   ```

4. **Démarrer l'environnement**
   ```bash
   stackmark start showme
   ```

5. **Vérifier le statut**
   ```bash
   stackmark status showme
   ```

6. **Ouvrir dans le navigateur**
   ```bash
   stackmark open showme
   ```

### Méthode 2 : Avec Docker Compose classique

1. **Copier le fichier de configuration**
   ```bash
   cp .env.docker .env
   ```

2. **Construire les images**
   ```bash
   docker-compose build
   ```

3. **Démarrer les conteneurs**
   ```bash
   docker-compose up -d
   ```

4. **Vérifier les logs**
   ```bash
   docker-compose logs -f
   ```

## Commandes utiles

### Avec Stackmark

```bash
# Démarrer le stack
stackmark start showme

# Arrêter le stack
stackmark stop showme

# Redémarrer le stack
stackmark restart showme

# Voir les logs
stackmark logs showme

# Voir les logs en temps réel
stackmark logs -f showme

# Dashboard interactif
stackmark dashboard

# Ouvrir l'application dans le navigateur
stackmark open showme

# Voir le statut de tous les stacks
stackmark list
```

### Avec Docker Compose

```bash
# Démarrer tous les services
docker-compose up -d

# Arrêter tous les services
docker-compose down

# Redémarrer un service spécifique
docker-compose restart app

# Voir les logs
docker-compose logs -f app

# Exécuter une commande dans le conteneur app
docker-compose exec app php artisan migrate

# Reconstruire les images
docker-compose build --no-cache
```

### Commandes Laravel courantes

```bash
# Migrations
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate:fresh --seed

# Cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimisation
docker-compose exec app php artisan optimize
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache

# Tinker
docker-compose exec app php artisan tinker

# Générer des helpers IDE
docker-compose exec app php artisan ide-helper:generate
docker-compose exec app php artisan ide-helper:models
docker-compose exec app php artisan ide-helper:meta

# Tests
docker-compose exec app php artisan test
docker-compose exec app vendor/bin/pest

# Queue
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan queue:restart
```

### Commandes Composer et NPM

```bash
# Installer les dépendances PHP
docker-compose exec app composer install

# Mettre à jour les dépendances PHP
docker-compose exec app composer update

# Installer les dépendances Node
docker-compose exec vite npm install

# Build des assets pour production
docker-compose exec vite npm run build
```

## Accès aux services

Une fois l'environnement démarré, vous pouvez accéder à :

- **Application Laravel** : http://localhost:8000
- **Vite HMR** : http://localhost:5173
- **Mailpit (Interface Web)** : http://localhost:8025
- **MySQL** : localhost:3306
- **Redis** : localhost:6379

## Configuration des permissions

Pour éviter les problèmes de permissions, assurez-vous que les variables `USER_ID` et `GROUP_ID` dans `.env` correspondent à votre utilisateur système :

```bash
# Obtenir votre USER_ID et GROUP_ID
id -u  # USER_ID
id -g  # GROUP_ID
```

Mettez à jour ces valeurs dans le fichier `.env`.

## Volumes et persistance

Les données sont persistées dans des volumes Docker :

- `mysql-data` : Données de la base MySQL
- `redis-data` : Données Redis
- `node-modules` : Dépendances Node.js (pour de meilleures performances)

Le code source est monté en bind mount pour permettre le développement en temps réel.

## Résolution de problèmes

### Les conteneurs ne démarrent pas

1. Vérifier les logs :
   ```bash
   stackmark logs showme
   # ou
   docker-compose logs
   ```

2. Vérifier les ports :
   ```bash
   # Vérifier si les ports sont déjà utilisés
   sudo netstat -tulpn | grep -E '(8000|3306|6379|5173|8025)'
   ```

3. Reconstruire les images :
   ```bash
   docker-compose down -v
   docker-compose build --no-cache
   docker-compose up -d
   ```

### Erreurs de permissions

Si vous rencontrez des erreurs de permissions :

```bash
# Dans le conteneur
docker-compose exec app chown -R laravel:laravel storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### La base de données n'est pas accessible

Assurez-vous que MySQL est démarré et prêt :

```bash
docker-compose exec mysql mysqladmin ping -h localhost
```

### Vite HMR ne fonctionne pas

1. Vérifiez que le service vite est démarré :
   ```bash
   docker-compose ps vite
   ```

2. Vérifiez la configuration dans `vite.config.js` :
   Le HMR doit écouter sur `0.0.0.0` pour être accessible depuis l'hôte.

### Réinitialiser complètement l'environnement

```bash
# Arrêter et supprimer tous les conteneurs, volumes et réseaux
docker-compose down -v

# Reconstruire et redémarrer
docker-compose build --no-cache
docker-compose up -d

# Réinstaller les dépendances
docker-compose exec app composer install
docker-compose exec vite npm install

# Réexécuter les migrations
docker-compose exec app php artisan migrate:fresh --seed
```

## Configuration pour la production

Pour passer en production, modifiez les fichiers suivants :

1. **`.env`**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=mysql
   CACHE_STORE=redis
   QUEUE_CONNECTION=redis
   ```

2. **`docker/php/conf.d/laravel.ini`**
   ```ini
   display_errors = Off
   display_startup_errors = Off
   error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
   ```

3. **`docker/php/conf.d/opcache.ini`**
   ```ini
   opcache.validate_timestamps = 0
   opcache.revalidate_freq = 0
   ```

4. **Optimiser l'application**
   ```bash
   docker-compose exec app php artisan optimize
   docker-compose exec app composer install --no-dev --optimize-autoloader
   docker-compose exec vite npm run build
   ```

## Sauvegarde et restauration

### Sauvegarde de la base de données

```bash
# Créer une sauvegarde
docker-compose exec mysql mysqldump -u showme -psecret showme > backup.sql

# Avec compression
docker-compose exec mysql mysqldump -u showme -psecret showme | gzip > backup.sql.gz
```

### Restauration de la base de données

```bash
# Restaurer depuis un fichier SQL
docker-compose exec -T mysql mysql -u showme -psecret showme < backup.sql

# Restaurer depuis un fichier compressé
gunzip < backup.sql.gz | docker-compose exec -T mysql mysql -u showme -psecret showme
```

## Support et documentation

- Documentation Laravel : https://laravel.com/docs/12.x
- Documentation Livewire : https://livewire.laravel.com/docs
- Documentation Docker : https://docs.docker.com
- Documentation Stackmark : https://www.stackmark.tech

## Auteur

Configuration Docker créée pour le projet ShowMe - Application de suivi de perte de poids
