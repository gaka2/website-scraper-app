## Prerequisites
Docker installed

## Setup an environment

### Build Docker container
```bash
export UID=$(id -u)
export GID=$(id -g)
docker-compose up -d --build
```

### Enter 'php' container
```bash
docker-compose exec php /bin/bash
```

### Install dependencies
```bash
composer install
```

### Run application
```bash
php bin/console app:get-product-options
```

### Run unit tests
```bash
XDEBUG_MODE=coverage ./vendor/phpunit/phpunit/phpunit
```

### View test report in HTML
```bash
report/index.html
```
