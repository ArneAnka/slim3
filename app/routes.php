<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app->group('', function () {
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');

    $this->get('/dashboard', 'AuthController:dashboard')->setName('dashboard');

    $this->get('/notes', 'NoteController:index')->setName('notes');
    $this->get('/notes/{note_id:[0-9]+}', 'NoteController:getEditNote');
    $this->post('/notes/{note_id:[0-9]+}', 'NoteController:postEditNote')->setName('edit.note');
    $this->get('/notes/deleteNote/{note_id:[0-9]+}', 'NoteController:deleteNote')->setName('delete.note');
    $this->post('/notes', 'NoteController:newNote')->setName('new.note');
})->add(new AuthMiddleware($container));