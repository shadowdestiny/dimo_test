# Diimo

> Diimo API and Dashboard

## Installation

-   `git clone https://github.com/elaniin/diimo.git`
-   `cd diimo` or name that you defined
-   `cp .env.example .env`
-   `composer install`
-   `php artisan key:generate`
-   Edit `.env` and set your database connection details
-   `php artisan migrate`
-   `php artisan db:seed`
-   `yarn or npm install`
-   `yarn dev or npm run dev`

## Usage

### Local Development Server

You can route serve with artisan CLI

```bash
php artisan serve
```

#### Development

```bash
# build and watch
yarn watch

# serve with hot reloading
yarn hot

# for up
```

### Note

Recommended user [git flow](https://danielkummer.github.io/git-flow-cheatsheet/) for branch and release management.

#### Production

```bash
yarn production
```
