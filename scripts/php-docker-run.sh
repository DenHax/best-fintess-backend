#!/usr/bin/env

docker build -t Dockerfile
docker run --name php-container -p 8080:8080
