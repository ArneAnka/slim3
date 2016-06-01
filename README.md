# Slim 3 Skeleton

This is a skeleton project for Slim 3 that includes Twig, TwigExtension, Flash messages, and Eloquent DB connection.

Users can sign-up and sign-in.

## Create your project:

1. First in your terminal: `$ git clone https://github.com/arneanka/slim3 slim3`
2. Then move inside that directory, `$ cd slim3`
3. Then run composer, `$ composer update`

### Run it:

1. `$ cd slim3` (the root of the git clone)
2. `$ php -S 0.0.0.0:8888 -t public public/index.php`
3. Browse to http://localhost:8888

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
* Ditch Eloquent for use of FluentPDO?
* Simple CRUD