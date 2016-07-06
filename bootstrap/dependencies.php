<?php
$container = $app->getContainer();

/**
* Eloquent
*/
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/**
* Attach Eloquent to $container
*/
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig($container['settings']['view']['template_path'], $container['settings']['view']['twig']);

    // Extensions to view
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new App\TwigExtension\diffForHumans());

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

/**
* Custom 404
* Override the default Not Found Handler
*/
// $container['notFoundHandler'] = function ($c) {
//     return function ($request, $response) use ($c) {
//         $c['view']->render($response, 'errors/404.twig');
//         return $response->withStatus(404);
//     };
// };

/**
* Custom CSRF fail response
* Throw a "Method not allowed" error message if CSRF check fails.
*/
$container['csrf'] = function ($container) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};
// $container['csrf'] = function ($container) {
//     return new \Slim\Csrf\Guard;
// };

$container['validator'] = function ($container) {
    return new \App\Validation\Validator;
};

/**
* Attach controllers to $container
*/
$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['NoteController'] = function ($container) {
    return new \App\Controllers\NoteController($container);
};

$container['ProfileController'] = function ($container) {
    return new \App\Controllers\ProfileController($container);
};

$container['AdminController'] = function ($container) {
    return new \App\Controllers\AdminController($container);
};