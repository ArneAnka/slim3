<?php

/**
* Add your "extra" middleware
*/
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\SubscriberMiddleware;

// // Render a view immediately from routes.php with and arguments
// $app->get('/@{username}', function ($request, $response, $args) {
//     return $this->view->render($response, 'profiles/index.twig', ['username' => $args['username']]);
//     // return 'Hello ' . $args['user'] .', nice to meet you!';
// });

/* json example for API */
$app->get('/json', function ($request, $response){
    $data = array('user_name' => 'John Doe','email' => 'john.doe@example.com');
    return $response->withJson($data);
});

$app->get('/', 'HomeController:index')->setName('home');

$app->get('/@{user_name}', 'ProfileController:getIndex'); //alpha & numeric

$app->group('', function () {
    $this->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/signup', 'AuthController:postSignUp');

    $this->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/password/change', 'PasswordController:postChangePassword');

    $this->get('/dashboard', 'AuthController:dashboard')->setName('dashboard');

    $this->get('/notes', 'NoteController:index')->setName('notes');
    $this->post('/notes', 'NoteController:newNote')->setName('new.note');
    
    $this->get('/notes/{note_id:[0-9]+}', 'NoteController:getEditNote');
    $this->put('/notes/{note_id:[0-9]+}', 'NoteController:postEditNote')->setName('edit.note');
    $this->get('/notes/deleteNote/{note_id:[0-9]+}', 'NoteController:deleteNote')->setName('delete.note');
})->add(new AuthMiddleware($container));

$app->group('', function () {
    $this->get('/admin', 'AdminController:getIndex')->setName('admin.index');
    $this->post('/admin', 'AdminController:postIndex')->setName('admin.post');
})->add(new AdminMiddleware($container));

/* Subscription */
$app->get('/subscription', function ($request, $response) {
    return $this->view->render($response, 'subscriber/index.twig');
})->add(new SubscriberMiddleware($container));