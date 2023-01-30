## Prerequisites
Docker installed

## Setup an environment

### Build Docker container
```bash
export UID=$(id -u)
export GID=$(id -g)
docker-compose up -d --build
docker-compose exec php /bin/bash
```
