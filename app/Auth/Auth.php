<?php

namespace App\Auth;

use App\Models\User;

class Auth
{
    /**
    * return user ID in.
    *
    * @return mixed
    */
    public function user()
    {
        if(!empty($_SESSION['user_id']))
        {
            return User::find($_SESSION['user_id']);
        }
        return false;
    }

    /**
    * Check if the user is signed in or not.
    *
    * @return bool
    */
    public function check()
    {
        return isset($_SESSION['user_id']);
    }

    /**
    * Check if the user indeed is the admin
    *
    * @param $_SESSION['user_id']
    *
    * @return bool
    */
    public function checkIsAdmin()
    {
        /* Is the user signed in? */
        if(!$this->check()){
            return false;
        }

        /* If the user is signed in, then see if the user is admin */
        $signed_in_user = User::where('user_id', $_SESSION['user_id'])->first()->user_account_type;

        if($signed_in_user == '1'){
           return true;
       }else{
            return false;
       }
    }

    /**
    * Attampt to sign in the user.
    *
    * @param $email
    * @param $password
    *
    * @return bool
    */
    public function attempt($email, $password)
    {
        /* Try and fetch user information DB */
        $user = User::where('user_email', $email)->first();

        /* If no user data was found, return false */
        if (!$user) {
            return false;
        }

        /* If user is marked as deleted, return false */
        if($user->user_deleted){
            return false;
        }

        /**
        * If user has passed the ban time, reset the suspension timestamp to NULL
        */
        if($user->user_suspension_timestamp){
            $date = date('Y-m-d H:i:s');
            if($user->user_suspension_timestamp > $date){
                return false;
            }else if($user->user_suspension_timestamp < $date){
                $user->user_suspension_timestamp = NULL;
                $user->save();
            }
        }

        if (password_verify($password, $user->user_password_hash)) {
            // Session::init();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['user_email'] = $user->user_email;

            $user->session_id = session_id();
            $user->save();

            return true;
        }

        return false;
    }

    /**
    * sign out the user by simply unset the session user_id
    * TODO: user should be signed-in with session in DB
    */
    public function logout()
    {
        $user = User::where('user_email', $_SESSION['user_email'])->first();
        $user->session_id = NULL;
        $user->save();
        unset($_SESSION['user_id']);
    }
}