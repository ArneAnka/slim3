# Slim 3 Skeleton

This is a skeleton project for Slim 3 that includes Twig, TwigExtension, Flash messages, and Eloquent DB connection.

Users can sign-up and sign-in.

## Create your project:

1. First in your terminal: `$ git clone https://github.com/arneanka/slim3 slim3`
2. Then move inside that directory, `$ cd slim3`
3. Then run composer, `$ composer update`

### Run it:

1. Open terminal, `$ cd slim3` (the root of the git clone)
2. Type `$ php -S 0.0.0.0:8888 -t public public/index.php`
3. Create the database file, `$ touch resources/database.sqlite`
4. Open folder `_installation` and follow the steps.
5. Browse to http://localhost:8888
6. Sign in with `john@example.com` and password `123`

## Key directories

* `app`: Application code
* `app`: All class files within the `App` namespace
* `resources/views`: Twig template files
* `cache/twig`: Twig's Autocreated cache files
* `public`: Webserver root
* `vendor`: Composer dependencies

## Key files

* `public/index.php`: Entry point to application
* `bootstrap/settings.php`: Configuration
* `bootstrap/dependencies.php`: Services for Pimple
* `bootstrap/middleware.php`: Application middleware
* `app/routes.php`: All application routes are here
* `app/Controllers/Controllers/HomeAction.php`: Home controller for the home page
* `resources/views/home.twig`: Twig template file for the home page

## TODO
* So many things to do. Much todo