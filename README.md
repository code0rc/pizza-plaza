# Projekt zur Umsetzung der Programmieraufgaben für Praktikanten mit Schwerpunkt Anwendungsentwicklung
© elio GmbH, 2019

## Installation
*Before you continue, please make sure you have a recent version of npm and composer installed on your system.*

Run `npm install` to load all dependencies and then run `npm run init` to copy assets into the public document root.
Next, run `composer dump-autoload --optimize`

## Execution
### Docker Runtime
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

### Configuration
The web app's configuration is stored locally on a per-project basis. To successfully run the project, you need to 
create a copy of the `config.example.json` file and rename it to `config.json`. Then enter the correct values for your 
setup and save the file. Please do **not** commit this file into version control since it contains sensitive 
information including database credentials among others.

#### Database
Enter your setup's database credentials under the `database` node.

#### Backend
Enter your backend username and password under the `backend` node.

## Development
### Vue Components
Vue components are transpiled to ES2015 and then concatenated and minified. This means that, after any modifications to 
the vue part of this project, assets must be compiled again before changes will take effect. For your convenience, 
the `npm run init` task has been updated to include the (re)compilation of the necessary scripts. Whenever you modify 
anything in the `src` directory, simply run `npm run init` again, and you should be good to go. If you're making 
frequent changes to the component files, you can also start a watcher task that will recompile your JavaScript files 
every time it detects a change. To start the watcher task, simply execute `npm run watch:scripts`.
**If you need to add new components, you also need to register them in the `gulpfile.js` in correct order.**

## Deployment
Since this project doesn't have any deployment target, it would be a waste of resources to pick a deployment tool and 
set up a pipeline. This makes it necessary to add all compiled assets to version control. **Always run `npm run init` 
and add all generated assets to Git's staging area before committing your changes.**

## Usage
### Admin Panel
To access the admin panel, navigate to `?site=admin` and enter your backend login credentials.
Make sure you've configured the backend user in the `config.json` file (see section [Configuration](#configuration)).
