Backend
=======

Installation
------------

Le backend tourne dans Docker. Depuis `deployment/docker/` :

```shell
# première fois : copier et adapter les credentials
cp .env.example .env

# démarrer mariadb + backend
docker-compose up -d mariadb backend

# première fois : installer les dépendances et initialiser la base
docker-compose exec backend composer install
docker-compose exec backend php bin/console doc:mig:mig
docker-compose exec backend php bin/console assets:install
```

Go to http://localhost:8000/api/docs

> Note : le `DATABASE_URL` est injecté automatiquement par docker-compose.
> Le fichier `backend/.env.local` n'a pas besoin de le définir.

Tests
-----

Lancer `mariadb_test` (port 3307 sur le host, pour éviter le conflit avec `mariadb`) :

```shell
# depuis deployment/docker/
docker-compose up -d mariadb_test
```

Puis depuis `backend/` en local :

```shell
# php cs fixer
composer cs
# phpstan
composer ps
# phpunit
composer tu
```

Ou tout dans le container backend (avec les deux bases démarrées) :

```shell
docker-compose exec backend composer cs
docker-compose exec backend composer ps
docker-compose exec backend composer tuci
```
