Gestion de micro entreprise
=============

Manage your contacts, opportunities, tenders, incomes, taxes and costs in this amazing application. 

[![In Progress](https://img.shields.io/badge/in%20progress-yes-red)](https://img.shields.io/badge/in%20progress-yes-red)

[![backend](https://github.com/mp3000mp/auto-entreprise/actions/workflows/backend.yml/badge.svg)](https://github.com/mp3000mp/auto-entreprise/actions/workflows/backend.yml)
[![codecov](https://codecov.io/gh/mp3000mp/auto-entreprise/graph/badge.svg?token=Z08REIAIKM&flag=backend)](https://codecov.io/gh/mp3000mp/auto-entreprise&flag=backend)

[![frontend](https://github.com/mp3000mp/auto-entreprise/actions/workflows/frontend.yml/badge.svg)](https://github.com/mp3000mp/auto-entreprise/actions/workflows/frontend.yml)
[![codecov](https://codecov.io/gh/mp3000mp/auto-entreprise/graph/badge.svg?token=Z08REIAIKM&flag=frontend)](https://codecov.io/gh/mp3000mp/auto-entreprise&flag=frontend)

This is just a POC.

Documentation
-------------

todo

Installation
------------

Clone this repository
```
git clone https://github.com/mp3000mp/auto-entreprise.git
```

Install scripts and database
```shell
composer install
npm install
npm run build
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixtures:load
```

OR 
 - change ansible/inventory/hosts
 - change ansible/vars.yml 
 - change ansible/roles/build_back/template/.env.local.j2

```shell
ansible-playbook -i ansible/inventory/hosts ansible/site.yml
```

Copy .env to .env.local and adapt to your configuration


License
-------

This bundle is under the Apache 2.0 license. See the complete license [in the bundle](LICENSE)

About
-----

This project is a [mp3000mp](https://github.com/mp3000mp) initiative.
