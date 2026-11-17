# La Guilde des Seigneurs

Symfony monorepo: JWT REST API and Twig web front for the Guilde des Seigneurs game project.

## Overview

EIP Symfony project. The front consumes the API via `API_URL` with JWT stored in session.

## Features

- JWT authentication
- Doctrine ORM + MySQL
- OpenAPI on the API
- Twig + Bootstrap front

## Stack

- PHP 8.2+, Symfony 7.4
- Doctrine, Lexik JWT, OpenAPI
- Twig, Bootstrap, Symfony HttpClient
- MySQL 8

## Layout

```
symfony-project/
├── api/     → REST API (JWT, Doctrine, OpenAPI)
└── front/   → Web app (Twig, Bootstrap)
```

## Run

```bash
cd api
composer install
cp .env.example .env.local
# set APP_SECRET, DATABASE_URL

cd ../front
composer install
cp .env.example .env.local
# set APP_SECRET, DATABASE_URL, API_URL
```

Create MySQL databases `guilde_seigneurs` (API) and `guilde_seigneurs_full` (front), then run migrations / load SQL seeds from each app as documented in-app.
