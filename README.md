# Projekt zur Umsetzung der Programmieraufgaben für Praktikanten mit Schwerpunkt Anwendungsentwicklung
© elio GmbH, 2019

## Installation

Run `npm install` to load all dependencies and then run `npm run init` to copy assets into the public document root. 

## Execution

*Before continuing, make sure you have Docker installed.*

Run `docker-compose up -d` to start the containerized runtime environment (PHP, MariaDB, NGINX).
Your project directory will be mounted into the containers.

To access the web app, navigate to `http://localhost` in your browser. You may need to stop other services that are 
using ports `80` and `3306` on your host system. If this is unacceptable for you, you can override the environment 
variables set in the `.env` file to modify this behavior.

**Attention**: Please don't modify the original `.env` file to override environment variables. You should instead set 
the environment variables on your shell or your IDE. Alternatively you can copy the `.env` file into a new `.env.local` 
file and start the containers using `docker-compose --env-file .env.local up -d`. For this option to work correctly, 
all environment variables specified in the original `.env` file must be set in the copy as well.
