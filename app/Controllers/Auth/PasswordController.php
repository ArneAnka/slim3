<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'user_password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->user_password_hash),
            'user_password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $this->auth->user()->setPassword($request->getParam('user_password'));

        $this->flash->addMessage('info', 'Your password was changed.');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}
