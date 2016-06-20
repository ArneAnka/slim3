<?php

namespace App\Auth;

use App\Models\User;

class Auth
{
    public function user()
    {
        if(!empty($_SESSION['user_id']))
        {
            return User::find($_SESSION['user_id']);
        }
        return false;
    }

    public function check()
    {
        return isset($_SESSION['user_id']);
    }

    public function checkIsAdmin()
    {
        if(User::where('user_id', $_SESSION['user_id'])->first()->user_account_type == 1){
           return true;
       }else{
            return false;
       }
    }

    public function attempt($email, $password)
    {
        $user = User::where('user_email', $email)->first();

        if (!$user) {
            return false;
        }

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

    public function logout()
    {
        $user = User::where('user_email', $_SESSION['user_email'])->first();
        $user->session_id = NULL;
        $user->save();
        unset($_SESSION['user_id']);
    }
}