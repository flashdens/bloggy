<p align="center">
<img src="app/public/img/logotext.png" alt="logo">
</p>

### EN

**Bloggy** is a web blog app written in **PHP** using the **Symfony** framework. 
It offers a *beautiful* user interface that lets you write posts, comment on them,
end even set your own avatar. It also features an administration panel that lets you CRUD everything.
Bloggy was written as an uni project for the **Interactive System** subject.

### PL

**Bloggy** to aplikacja webowa, będąca blogiem internetowym, napisana w **PHP** z użyciem frameworka **Symfony**.
Oferuje *przepiękny* interfejs użytkownika pozwalający na pisanie postów i komentarzy, a nawet ustawienie własnego awatara.
Zawiera również potężny panel administratora, który pozwala na CRUDowanie wszystkim (chodzi o zawartość - takie rzeczy może robić tylko admin).
Aplikacja napisana jako zaliczenie na przedmiot **System interakcyjny - projekt**.

## A short guide to set up **bloggy** on your machine 

0. Install [Docker](https://www.docker.com/products/docker-desktop) and [Docker Compose](https://docs.docker.com/compose/install) on your machine
1. Clone the git repository to the desired folder
2. Build the containers with ``./build.env.sh`` 
3. Enter the environment with ``docker-compose exec php bash``
4. Specify the MySQL database connection URL in the ``.env`` file:
```yaml
DATABASE_URL=mysql://symfony:symfony@mysql:3306/symfony?serverVersion=5.7
```
5. Set up the database and load sample content to it wih:
  ``
    bin/console doctrine:migrations:migrate
    bin/console doctrine:fixtures:load
  ``
6. The app should be accessible at ``localhost:8000``. You can find the login credentials at ``app/src/DataFixtures/UserFixtures``. 

## Database structure

<p align="center">
<img src="app/public/img/screenshots/diagram.png" alt="db structure">
</p>

## Screenshots

<p align="center">
<img src="app/public/img/screenshots/screen1.png" alt="Screenshot 1">
</p>

<p align="center">
<img src="app/public/img/screenshots/screen2.png" alt="Screenshot 2">
</p>

<p align="center">
<img src="app/public/img/screenshots/screen4.png" alt="Screenshot 3">
</p>

## What is inside?

* Apache 2.4.25 (Debian)
* PHP 8.1 FPM
* MySQL 8.0.x (5.7)
* NodeJS LTS (latest)
* Composer
* Symfony CLI 
* xdebug
* djfarrelly/maildev

## Requirements

* Install [Docker](https://www.docker.com/products/docker-desktop) and [Docker Compose](https://docs.docker.com/compose/install) on your machine 

## Installation

* (optional) Add 

```bash
127.0.0.1   symfony.local
```
in your `host` file.

* Run `build-env.sh` (or `build-env.ps1` on Windows box)

* Enter the PHP container:

```bash
docker-compose exec php bash
```

* To install Symfony LTS inside container execute:

```bash
cd app
rm .gitkeep
git config --global user.email "you@example.com"
symfony new ../app --full --version=lts
chown -R dev.dev *
```

## Container URLs and ports

* Project URL

```bash
http://localhost:8000
```

or 

```bash
http://symfony.local:8000
```

* MySQL

    * inside container: host is `mysql`, port: `3306`
    * outside container: host is `localhost`, port: `3307`
    * passwords, db name are in `docker-compose.yml`
    
* djfarrelly/maildev i available from the browser on port `8001`

* xdebug i available remotely on port `9000`

* Database connection in Symfony `.env` file:
```yaml
DATABASE_URL=mysql://symfony:symfony@mysql:3306/symfony?serverVersion=5.7
```

## Useful commands

* `docker-compose up -d` - start containers
* `docker-compose down` - stop contaniners
* `docker-compose exec php bash` - enter into PHP container
* `docker-compose exec mysql bash` - enter into MySQL container
* `docker-compose exec apache bash` - enter into Apache2 container

dżungle