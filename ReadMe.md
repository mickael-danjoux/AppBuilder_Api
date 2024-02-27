# Symfony App Api

All required configs and packages for starting an API for mobile apps.

## Config
- PHP 8.2
- Symfony CLI
- Symfony 7
- Docker

## Start

### Install dependencies
```shell
symfony composer install
```

### Start server

Let’s run the symfony server! It will also run the `docker-compose.dev.yml`.
```shell
symfony serve
```

### Stop docker
```shell
docker-compose -f docker-compose.dev.yml down
```

## Informations

- Doc is available under [https://127.0.0.1:8000/api](https://127.0.0.1:8000/api)
- Login is using ``Firebase``

### Tests

- User CRUD 


```shell
symfony php bin/phpunit
```

