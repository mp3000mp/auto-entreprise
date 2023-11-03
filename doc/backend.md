Backend
=======

Installation
------------

```shell
# install dependecies
composer install
# create database
bin/console doc:data:create
# exec migrations
bin/console doc:mig:mig
# install api platform assets
bin/console assets:install
# run dev server
symfony serve:start
```

Go to localhost:8000/api/docs

Tests
-----

```shell
# php cs fixer
composer cs
# phpstan
composer ps
# phpunit
composer tu
```
