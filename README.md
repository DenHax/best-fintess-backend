# The Best Fitness service

Tech stack: PHP 8.2, Apache, Postgres 17.0

Architecture: MVC, RestAPI

Dependencies: Docker, Docker Compose

Startup:

```sh
docker compose -f compose.yaml up
```

React В docker
Если удаляем папку на хосте, она восстанавливается из docker

Восстановление данных для базы данных:

```sh
docker exec -i fit-psql psql \
  -U fit-admin \
  -d fitness \
  -v ON_ERROR_STOP=1 \
 < ./backup/fitness_backup.sql
```
