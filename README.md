
## Setup

```shell
cp .env.example .env
```

```shell
docker compose up -d --build
```

## Enter the container
```shell
docker compose exec app bash
```

## Install dependencies
```shell
composer install
```

## Run example
The algorithm is not fully functional yet, but you can run it to see the current state of the code.
```
php artisan app:calculate
```
