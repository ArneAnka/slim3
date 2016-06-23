<?php

namespace App\Controllers;

use App\Models\User;
use Slim\Views\Twig as View;
use Respect\Validation\Validator as v;

class AdminController extends Controller
{
    /**
    * Fetch all user rows
    *
    * @return user_id, user_name, user_password_hash, user_email, updated_at, created_at, user_account_type_,
    * user_profile, user_deleted, user_slug, user_suspension_time, session_id
    */
    public function getIndex($request, $response)
    {
	    $users = User::all();

	    return $this->view->render($response, 'auth/admin/index.twig', ['users' => $users]);
    }

    /**
    * If Admin submits form on admin page, both softdelete and suspension field will be
    * submitted.
    * @param suspension
    * @param softDlete
    * @param user_id
    *
    * @return bool
    */

    public function postIndex($request, $response){
    	/**
        * Check if the fields are valied.
        * suspension can be both negative and positive. To set, or to remove ban time.
        * softDelete is yes/no.
        *
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

        /**
        * Check if the suspension/ban should be removed.
        */
		if ($request->getParam('suspension') > 0) {
			$date = new \DateTime();
			$date->format('Y-m-d H:i:s');
			$suspensionTime = date_add($date, date_interval_create_from_date_string($request->getParam('suspension') . ' days'));
			$suspensionTime = date_format($suspensionTime,"Y-m-d H:i:s");
		} else {
			$suspensionTime = null;
		}

		/**
		* Fetch, and insert the values on that specefic user
		*/
		$user = User::where('user_id', '=', $request->getParam('user_id'))->first();

        /* Deny to alter other admin accounts */
        if($user->user_account_type == 1){
            $this->flash->addMessage('error', 'You cannot ban or suspend other admins.');
            return $response->withRedirect($this->router->pathFor('admin.index'));
        }

    	$user->user_suspension_timestamp = $suspensionTime;
    	$user->user_deleted = $request->getParam('softDelete');
    	$user->save();

        $this->flash->addMessage('success', 'User status has been set.');
    	return $response->withRedirect($this->router->pathFor('admin.index'));
    }
}
