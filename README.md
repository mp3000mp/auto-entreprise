# Gestion de micro entreprise

Manage your contacts, opportunities, tenders, incomes, taxes and costs in this amazing application. 

[![In Progress](https://img.shields.io/badge/in%20progress-yes-red)](https://img.shields.io/badge/in%20progress-yes-red)

[![backend](https://github.com/mp3000mp/auto-entreprise/actions/workflows/backend.yml/badge.svg)](https://github.com/mp3000mp/auto-entreprise/actions/workflows/backend.yml)
[![codecov](https://codecov.io/gh/mp3000mp/auto-entreprise/graph/badge.svg?token=Z08REIAIKM&flag=backend)](https://app.codecov.io/gh/mp3000mp/auto-entreprise?flags%5B0%5D=backend)

[![frontend](https://github.com/mp3000mp/auto-entreprise/actions/workflows/frontend.yml/badge.svg)](https://github.com/mp3000mp/auto-entreprise/actions/workflows/frontend.yml)
[![codecov](https://codecov.io/gh/mp3000mp/auto-entreprise/graph/badge.svg?token=Z08REIAIKM&flag=frontend)](https://app.codecov.io/gh/mp3000mp/auto-entreprise?flags%5B0%5D=frontend)

This is just a POC.


## Features

- **Contact/company** — todo

todo: screenshots

- **Opportunity/tender** — todo

todo: screenshots

- **TODO** — todo

todo: screenshots


## Self hosted deployment


Clone this repository
```
git clone https://github.com/mp3000mp/auto-entreprise.git
```

- change deployment/ansible/inventory/hosts
- copy deployment/ansible/vars.example.yml to deployment/ansible/vars.yml and change variables
- launch local_build.sh


```shell
ansible-playbook -i ansible/inventory/hosts ansible/site.yml
```


## Contributing

Contributions are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md).


## License

[MIT](LICENSE)

