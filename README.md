# symfony-project

Monorepo du projet **La Guilde des Seigneurs** (EIP Symfony) : API REST JWT et application web consommatrice.

## Structure

```
symfony-project/
  api/     API REST (Symfony 7.4, Doctrine, JWT, OpenAPI)
  front/   Application web (Symfony 7.4, Twig, Bootstrap, HttpClient)
```

Le front consomme l'API via `API_URL` avec authentification JWT en session.

## Prérequis

- PHP 8.2+
- Composer
- MySQL 8
- Laragon (recommandé) ou équivalent Apache/Nginx + PHP

## Installation

### 1. Dépendances

```bash
cd api
composer install
cp .env.example .env.local

cd ../front
composer install
cp .env.example .env.local
```

Éditer chaque `.env.local` : `APP_SECRET`, `DATABASE_URL`, et pour le front `API_URL`.

### 2. Bases de données

Créer deux bases MySQL :

- `guilde_seigneurs` (API)
- `guilde_seigneurs_full` (front)

### 3. Vhosts Laragon

| Domaine | Document root |
|---|---|
| `local.api.la-guilde-des-seigneurs.com` | `api/public` |
| `local.guilde-des-seigneurs.com` | `front/public` |

Ajouter les entrées dans `C:\Windows\System32\drivers\etc\hosts` si nécessaire.

### 4. Migrations et fixtures

```bash
cd api
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

cd ../front
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Les fixtures JSON sont dans `api/data/` (`characters.json`, `buildings.json`).

### 5. Assets front (optionnel)

```bash
cd front
php bin/console asset-mapper:compile
```

## URLs locales

| Service | URL |
|---|---|
| Front | http://local.guilde-des-seigneurs.com |
| API | http://local.api.la-guilde-des-seigneurs.com |
| Swagger / OpenAPI | http://local.api.la-guilde-des-seigneurs.com/api/doc |

## Tests

```bash
cd api && php bin/phpunit
cd ../front && php bin/phpunit
```

## Docker (optionnel)

Le dossier `front/` contient un `compose.yaml` avec PostgreSQL (template Symfony). Le projet est configuré pour MySQL en local Laragon. Adapter `DATABASE_URL` si vous utilisez Docker.

## Historique

Voir `api/ChangeLog.md` et `front/ChangeLog.md`.
