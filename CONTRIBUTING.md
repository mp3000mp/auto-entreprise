# Contributing

Thank you for your interest in Auto Entreprise. Contributions are welcome, though this is a small personal project without a formal roadmap.

## Bug reports

Open an issue describing:
- what you did
- what you expected
- what actually happened

Include your browser if relevant.

## Bug fixes

Open a pull request directly. No prior issue needed for bug fixes.

## Feature requests and feature PRs

Before implementing a new feature, open an issue to describe your idea and get approval from the maintainer. PRs for unapproved features will not be merged.

## Code requirements

- All CI checks must pass (type check, lint, unit tests).
- Test coverage must not decrease. Add tests for any new logic.
- Keep changes focused — one concern per PR.

## Development setup


### Frontend

```sh
npm install
npm run dev
```

Run tests:

```sh
npm run test:unit
npm run test:cov
```

Run linters:

```sh
npm run format
npm run lint
npm run build
```

### Backend

```sh
cd deployment/docker
docker-compose up -d
```

```sh
cd backend
composer install
```

Run tests:

```sh
composer tu
composer tuc  # with HTML coverage report
```

Run linters:

```sh
composer cs  # php-cs-fixer
composer ps  # phpstan
```

Adminer is available at http://localhost:5001.
