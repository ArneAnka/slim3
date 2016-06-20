<?php

namespace App\Controllers;

use App\Models\User;
use Slim\Views\Twig as View;
use Respect\Validation\Validator as v;

class AdminController extends Controller
{
    public function getIndex($request, $response)
    {
	    $users = User::all();

	    return $this->view->render($response, 'auth/admin/index.twig', ['users' => $users]);
    }

    public function postIndex($request, $response){

    	/**
        * Check if the fields are valied. op is a hidden field. To prevent bots
        */
        $validation = $this->validator->validate($request, [
            'suspension' => v::optional(v::intVal()), //->positive()
            'softDelete' => v::optional(v::boolVal()),
            'user_id' => v::intVal()
        ]);

        /**
        * If the fields fail, then redirect back to signup
        */
        if ($validation->failed()) {
        	$this->flash->addMessage('error', 'Something went wrong.');
            return $response->withRedirect($this->router->pathFor('admin.index'));
        }

		if ($request->getParam('suspension') > 0) {
			$date = new \DateTime();
			$date->format('Y-m-d H:i:s');
			$suspensionTime = date_add($date, date_interval_create_from_date_string($request->getParam('suspension') . ' days'));
			$suspensionTime = date_format($suspensionTime,"Y-m-d H:i:s");
		} else {
			$suspensionTime = null;
		}

		$user = User::where('user_id', '=', $request->getParam('user_id'))->first();
    	$user->user_suspension_timestamp = $suspensionTime;
    	$user->user_deleted = $request->getParam('softDelete');
    	session_destroy();

    	$user->save();

    	// return $this->view->render($response, 'auth/admin/index.twig', ['grejer' => $grejer]);
    	return $response->withRedirect($this->router->pathFor('admin.index'));
    }
}
