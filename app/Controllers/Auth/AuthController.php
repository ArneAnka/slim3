<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

        public function getSignOut($request, $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
    * Sign the user in with the provided credentials.
    *
    * @param string $user_email
    * @param string $user_password
    *
    * @return bool
    */
    public function postSignIn($request, $response)
    {

        $auth = $this->auth->attempt(
            $request->getParam('user_email'),
            $request->getParam('user_password')
        );

        if (!$auth) {
            $this->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        // If Auth successfull, then redirect to choosen location
        return $response->withRedirect($this->router->pathFor('dashboard'));
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    /**
    * Register a new user
    *
    * @param string $user_name
    * @param string $user_email
    * @param string $user_password
    *
    * @return bool
    */
    public function postSignUp($request, $response)
    {
        /**
        * Check if the fields are valied
        */
        $validation = $this->validator->validate($request, [
            'user_email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'user_name' => v::notEmpty()->alpha(),
            'user_password' => v::noWhitespace()->notEmpty(),
        ]);

        /**
        * If the fields fail, then redirect back to signup
        */
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        /**
        * If validation is OK, then continue with registration.
        */
        $user = User::create([
            'user_email' => $request->getParam('user_email'),
            'user_name' => $request->getParam('user_name'),
            'user_password_hash' => password_hash($request->getParam('user_password'), PASSWORD_DEFAULT),
        ]);

        /** Add a flas message that everything went ok **/
        $this->flash->addMessage('info', 'You have been signed up!');

        $this->auth->attempt($user->user_email, $request->getParam('user_password'));

        /** On success registration, redirect to dashboard */
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function dashboard($request, $response){
        return $this->view->render($response, 'auth/dashboard/dashboard.twig');
        // return $response->withRedirect($this->router->pathFor('dashboard'));
    }
}
