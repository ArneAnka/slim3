<?php

namespace App\Controllers;

use App\Models\User;
use Slim\Views\Twig as View;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class ProfileController extends Controller
{
	/**
	* Render all notes that we have in the DB
	*
	* @param null
	*
	* @return mixed
	*/
    public function getIndex($request, $response, $args)
    {
        if(User::where('user_name', $args['user_name'])->first()){
            return $this->view->render($response, 'profiles/index.twig', ['user' => User::where('user_name', $args['user_name'])->first()]);
        }else{
            // If no user found, throw and show 404
            return $response->withStatus(404)->withHeader('Content-Type', 'text/html')->write('Page not found');
        }

        // return $this->view->render($response, 'profiles/index.twig', ['username' => $args['username']]);
    }


}
