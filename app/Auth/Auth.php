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
        return null;
    }

    public function check()
    {
        return isset($_SESSION['user_id']);
    }

    public function checkIsAdmin()
    {
        if(User::where('user_id', $_SESSION['user_id'])->first()->user_account_type == '1'){
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

        if (password_verify($password, $user->user_password_hash)) {
            $_SESSION['user_id'] = $user->user_id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
    }
}