# ShowMe - Application de Suivi de Perte de Poids

Application web Laravel 12 avec Livewire pour suivre la perte de poids, les mesures corporelles et les repas avec calcul automatique des calories.

## Vue d'ensemble

ShowMe est une application complète de suivi de santé et fitness qui permet aux utilisateurs de :
- Suivre plusieurs métriques corporelles (poids, tour de taille, distance parcourue, etc.)
- Définir et suivre des objectifs de santé
- Enregistrer leurs repas avec calcul automatique des calories via IA
- Visualiser leurs progrès avec des graphiques et statistiques
- Calculer automatiquement leur TDEE (Total Daily Energy Expenditure)

## Technologies Utilisées

### Backend
- **PHP**: 8.4
- **Laravel Framework**: 12.0
- **Livewire Volt**: 1.7.0 (composants full-stack réactifs)
- **Flux UI**: 2.1 (composants Livewire)
- **Base de données**: PostgreSQL 16 (ou SQLite en local)
- **Cache & Queue**: Redis 7
- **OpenAI API**: GPT-4o-mini pour reconnaissance d'aliments

### Frontend
- **Vite**: 6.0
- **Tailwind CSS**: 4.0.7
- **Alpine.js**: (inclus via Livewire)

### DevOps
- **Docker**: Environnement de développement conteneurisé
- **Nginx**: 1.28
- **PostgreSQL**: 16-alpine
- **Redis**: 7-alpine
- **Mailpit**: Capture des emails en développement

## Architecture

### Structure des Dossiers
```
/home/jean-marc-strauven/Dev/showme/
├── app/
│   ├── Actions/              # Logique métier encapsulée
│   │   ├── Goals/
│   │   ├── Meals/
│   │   ├── Topics/
│   │   └── Values/
│   ├── Enums/               # Énumérations type-safe
│   │   ├── ActivityEnum.php
│   │   ├── GoalTypeEnum.php
│   │   └── UnitEnum.php
│   ├── Livewire/            # Composants Livewire
│   │   ├── Dashboard/
│   │   ├── Goals/
│   │   ├── Meals/
│   │   ├── Topics/
│   │   └── Values/
│   ├── Models/              # Modèles Eloquent
│   │   ├── User.php
│   │   ├── Topic.php
│   │   ├── Value.php
│   │   ├── Goal.php
│   │   ├── Meal.php
│   │   └── Configuration.php
│   ├── Observers/           # Observateurs de modèles
│   └── Services/            # Services métier
│       └── CalorieEstimationService.php
├── docker/                  # Configuration Docker
│   ├── nginx/
│   ├── php/
│   ├── postgres/
│   └── entrypoint.sh
├── resources/
│   └── views/               # Templates Blade
└── tests/                   # Tests Pest (39 fichiers)
```

### Modèle de Données

#### Tables Principales

**users**
- Informations personnelles: name, email, password
- Données biométriques: birth_at, height, gender
- Niveau d'activité: activity (ActivityEnum)
- TDEE calculé: calories_each_day

**topics**
- Métriques suivies (Weight, Waist Size, Distance, Calories, etc.)
- Champs: name, slug, description, unit (UnitEnum), is_weight
- Unités: kg, cm, km, km/h, cal, %

**values**
- Mesures individuelles pour chaque topic
- Calcul automatique de la différence avec la valeur précédente
- Couleur indicative (vert/rouge/bleu selon progression vers objectif)

**goals**
- Objectifs à atteindre pour chaque topic
- Types: increase, decrease, maintain
- Dates de début et fin

**meals**
- Enregistrement des repas
- Reconnaissance IA via photos
- Calcul automatique des calories

**configurations**
- Mappage des topics système (poids, calories in/out)
- Configuration utilisateur

## Fonctionnalités Principales

### 1. Gestion des Topics (/topics)
- Création de métriques personnalisées à suivre
- Définition de l'unité de mesure
- Types: poids, distances, calories, pourcentages

### 2. Suivi des Valeurs (/values)
- Enregistrement de mesures pour chaque topic
- Calcul automatique de la différence avec la dernière valeur
- Indicateurs visuels colorés (progression vers objectif)
- Filtrage par date

### 3. Objectifs (/goals)
- Définition de cibles pour chaque métrique
- Types d'objectifs:
  - **Increase**: augmenter une valeur
  - **Decrease**: diminuer une valeur
  - **Maintain**: maintenir une valeur
- Suivi du pourcentage de progression
- Projection de la date d'atteinte
- Statistiques: moyenne, min, max, fréquence

### 4. Dashboard (/)
- Vue d'ensemble de toutes les métriques
- Graphiques de progression
- Badge calorique quotidien (vert si sous l'objectif, rouge si au-dessus)
- Résumé des objectifs avec statistiques détaillées
- Analyse de tendance et projection

### 5. Repas (/meals)
- Enregistrement manuel (ingrédients + calories)
- **IA - Reconnaissance d'aliments**:
  - Upload d'une photo de repas
  - Analyse par GPT-4o-mini vision
  - Identification automatique des aliments
  - Estimation des calories
  - Création automatique de l'entrée
- Historique complet avec filtres
- Fonction de copie de repas récurrents

### 6. Calcul TDEE Automatique
- Formule Mifflin-St Jeor
- Facteurs: poids, taille, âge, sexe, niveau d'activité
- Mise à jour automatique lors des changements de poids
- Affichage dans le dashboard

### 7. Configuration (/configurations)
- Association des topics aux fonctions système
- Topic pour le poids (calcul TDEE)
- Topics pour calories in/out

### 8. Analytics Avancés
- Calcul de tendance (changement quotidien moyen)
- Score de progression
- Projection d'atteinte d'objectif
- Estimation de la date cible
- Suivi de la fréquence d'enregistrement

## Démarrage avec Docker

### Prérequis
- Docker et Docker Compose installés
- Stackmark CLI (recommandé)

### Configuration Initiale

1. **Cloner et configurer**:
```bash
cd /home/jean-marc-strauven/Dev/showme
cp .env.example .env
# Éditer .env et ajouter APP_KEY
```

2. **Variables d'environnement importantes**:
```env
APP_NAME=ShowMe
APP_KEY=base64:bnBWEKNkEY4B7ko5cBIDh7qiyoF8D6XMNJUNBPnY+JQ=
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=showme
DB_USERNAME=showme
DB_PASSWORD=secret

CACHE_STORE=redis
REDIS_HOST=redis
QUEUE_CONNECTION=redis

# OpenAI pour reconnaissance d'aliments (optionnel)
OPENAI_API_KEY=your_key_here

# AWS S3 pour stockage d'images (optionnel)
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
```

### Démarrage avec Stackmark

```bash
# Démarrer l'environnement
stackmark start showme

# Voir le statut
stackmark status showme

# Voir les logs
stackmark logs showme

# Arrêter
stackmark stop showme

# Redémarrer
stackmark restart showme

# Ouvrir dans le navigateur
stackmark open showme

# Dashboard interactif
stackmark dashboard
```

### Démarrage avec Docker Compose

```bash
# Build et démarrer
docker compose up -d

# Voir les logs
docker compose logs -f app

# Arrêter
docker compose down

# Rebuild après modifications
docker compose build --no-cache
docker compose up -d
```

### Services Disponibles

| Service | Port | URL | Description |
|---------|------|-----|-------------|
| **Application** | 9022 | http://localhost:9022 | Application Laravel |
| **PostgreSQL** | 9023 | localhost:9023 | Base de données |
| **Redis** | 9024 | localhost:9024 | Cache et queues |
| **Vite HMR** | 9025 | http://localhost:9025 | Hot Module Replacement |
| **Mailpit UI** | 9026 | http://localhost:9026 | Interface emails capturés |
| **Mailpit SMTP** | 9027 | localhost:9027 | Serveur SMTP local |

### Premier Démarrage

```bash
# Accéder au conteneur
docker exec -it showme-app sh

# Vider les caches si nécessaire
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Créer un utilisateur (si migrations vides)
php artisan migrate:fresh --seed

# Tester
curl http://localhost:9022
```

## Développement

### Commandes Composer Utiles

```bash
# Lancer tous les services en local (sans Docker)
composer dev

# Tests complets (lint, types, coverage)
composer test

# Formater le code
composer lint

# Analyse + Spelling + Review
composer clean
```

### Commandes Artisan Importantes

```bash
# Migrations
php artisan migrate
php artisan migrate:fresh --seed

# Cache
php artisan config:clear
php artisan route:cache  # Désactivé temporairement - bug Laravel 12
php artisan view:cache
php artisan cache:clear

# Queues
php artisan queue:work
php artisan queue:failed
php artisan queue:retry all

# Génération de clé
php artisan key:generate

# Storage link
php artisan storage:link
```

### Travailler avec Vite

```bash
# Développement avec HMR
npm run dev

# Build pour production
npm run build

# Dans Docker, le service vite est déjà configuré
```

## Tests

```bash
# Lancer tous les tests
php artisan test

# Avec coverage
php artisan test --coverage

# Test spécifique
php artisan test --filter=GoalTest

# Pest direct
./vendor/bin/pest
```

### Tests Disponibles
- 39 fichiers de tests feature
- Couverture: Authentication, Livewire Components, CRUD operations
- Target coverage: 70% minimum
- Type coverage: 100%

## Qualité du Code

### Outils de Qualité

- **Laravel Pint**: Formatage de code
- **PHPStan/Larastan**: Analyse statique
- **Rector**: Refactoring automatisé
- **Peck**: Vérification orthographique

### Standards

- Strict types sur tous les fichiers PHP
- Classes final (immutabilité)
- PHPDoc complet sur les modèles
- Enums pour les types (ActivityEnum, GoalTypeEnum, UnitEnum)

## Patterns Architecturaux

### Design Patterns Utilisés

1. **Action Pattern**: Logique métier dans des classes Action
2. **Observer Pattern**: Événements de modèles dans des Observers
3. **Service Pattern**: Logique complexe dans des Services
4. **Factory Pattern**: Factories pour les tests

### Observers Clés

- **ValueObserver**: Calcul automatique des différences et couleurs
- **MealObserver**: Gestion des événements repas

### Services Clés

- **CalorieEstimationService**:
  - Reconnaissance IA d'aliments via OpenAI
  - Calcul TDEE (formule Mifflin-St Jeor)
  - Estimation calorique

## Configuration Docker

### Architecture des Conteneurs

- **showme-app**: PHP 8.4-FPM + Nginx
- **showme-postgres**: PostgreSQL 16
- **showme-redis**: Redis 7 (cache + queues)
- **showme-vite**: Node 20 (HMR development)
- **showme-queue**: Worker Laravel pour queues
- **showme-mailpit**: Capture d'emails en développement

### Volumes

```yaml
volumes:
  - ./:/var/www/html                    # Code source
  - postgres-data:/var/lib/postgresql   # Données PostgreSQL
  - redis-data:/data                    # Données Redis
```

### Permissions

- Utilisateur: www-data (UID 1000, GID 1000)
- Permissions: 775 sur storage/ et bootstrap/cache/
- Correction automatique au démarrage via entrypoint.sh

### Network

Tous les services communiquent via le réseau `showme-network`.

## Dépannage

### Problèmes Courants

#### 1. Permission Denied sur storage/
```bash
docker exec showme-app chown -R www-data:www-data storage bootstrap/cache
docker exec showme-app chmod -R 775 storage bootstrap/cache
```

#### 2. APP_KEY Missing
```bash
# Dans .env, ajouter:
APP_KEY=base64:bnBWEKNkEY4B7ko5cBIDh7qiyoF8D6XMNJUNBPnY+JQ=

# Ou générer une nouvelle:
docker exec showme-app php artisan key:generate
```

#### 3. Cache de Configuration
```bash
docker exec showme-app php artisan config:clear
docker exec showme-app php artisan cache:clear
docker exec showme-app php artisan view:clear
```

#### 4. Base de Données Non Accessible
```bash
# Vérifier que PostgreSQL est healthy
docker ps --filter "name=showme-postgres"

# Tester la connexion
docker exec showme-postgres pg_isready -U showme
```

#### 5. Redis Non Accessible
```bash
# Vérifier Redis
docker exec showme-redis redis-cli ping
```

#### 6. Conteneurs en Restart Loop
```bash
# Voir les logs
docker logs showme-app --tail 50

# Problèmes fréquents:
# - nginx.conf avec mauvais utilisateur (doit être www-data)
# - APP_KEY manquante
# - route:cache qui échoue (désactivé dans entrypoint.sh)
```

### Logs Utiles

```bash
# Logs application
docker logs showme-app -f

# Logs Laravel
docker exec showme-app tail -f storage/logs/laravel.log

# Logs Nginx
docker exec showme-app tail -f /var/log/nginx/error.log

# Logs PostgreSQL
docker logs showme-postgres -f

# Logs Queue Worker
docker logs showme-queue -f
```

## Contribution

### Workflow de Développement

1. Créer une branche feature
2. Développer la fonctionnalité
3. Écrire les tests
4. Lancer `composer clean` (lint + types + spelling)
5. Vérifier que tous les tests passent
6. Créer une Pull Request

### Standards de Code

- Suivre PSR-12
- Utiliser Laravel Pint pour le formatage
- Tous les tests doivent passer
- Coverage minimum: 70%
- Type coverage: 100%

## Sécurité

### Bonnes Pratiques

- Ne jamais committer `.env`
- Garder `APP_KEY` secrète
- Utiliser HTTPS en production
- Valider toutes les entrées utilisateur
- Utiliser les migrations pour la structure DB
- Observer patterns pour éviter les injections
- Rate limiting sur les endpoints sensibles

### Variables Sensibles

```env
# À ne JAMAIS committer
APP_KEY=...
DB_PASSWORD=...
OPENAI_API_KEY=...
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
```

## Production

### Checklist de Déploiement

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Générer une nouvelle `APP_KEY`
- [ ] Configurer PostgreSQL en production
- [ ] Configurer Redis en production
- [ ] Configurer AWS S3 pour les uploads
- [ ] Configurer un vrai serveur SMTP
- [ ] Activer HTTPS
- [ ] Configurer les backups DB
- [ ] Optimiser (config:cache, route:cache, view:cache)
- [ ] Configurer la queue avec supervisor
- [ ] Logs monitoring (Sentry, Rollbar, etc.)

### Optimisations Production

```bash
# Optimiser l'autoload
composer install --optimize-autoloader --no-dev

# Cacher les configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimiser les événements
php artisan event:cache
```

## Ressources

### Documentation

- [Laravel 12](https://laravel.com/docs/12.x)
- [Livewire](https://livewire.laravel.com)
- [Tailwind CSS 4](https://tailwindcss.com)
- [Pest PHP](https://pestphp.com)

### Stack

- PHP 8.4
- Laravel 12
- PostgreSQL 16
- Redis 7
- Nginx 1.28
- OpenAI GPT-4o-mini

---

**Développé avec Laravel 12 + Livewire**
Pour toute question, consulter la documentation Laravel ou ouvrir une issue.
