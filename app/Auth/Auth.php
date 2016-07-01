<?php

namespace App\Auth;

use App\Models\User;

class Auth
{
    /**
    * Return user ID.
    *
    * Fetch with $this->container->auth->check()
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
    * Attempt to sign in the user.
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

        /**
        * Password throttling
        *
        * brute force attack mitigation: use session failed login count and last failed login for not found users.
        * block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        * (limits user searches in database). Change 30 to the amount of seconds you want the user NOT to be
        * able to try another login.
        */
        if(isset($_SESSION['failed-login-count']) && isset($_SESSION['last-failed-login'])){
            if($_SESSION['failed-login-count'] >= 3 AND ($_SESSION['last-failed-login'] > (time() - 30))) {
            return false;
            }
        }

        /* If no user data was found, return false */
        if(!$user) {
            // increment the user not found count, helps mitigate user enumeration
            $this->incrementUserNotFoundCounter();
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (($user->user_failed_logins >= 3) AND ($user->user_last_failed_login > (time() - 30))) {
            return false;
        }

        /* If user is marked as deleted, return false */
        if($user->user_deleted){
            return false;
        }

        /**
        * Check if user is banned.
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


        /**
        * Verify user password with the PHP password_verify().
        * Write the session to the DB.
        * http://www.phptherightway.com/#password_hashing
        */
        if(password_verify($password, $user->user_password_hash)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['user_email'] = $user->user_email;

            $user->session_id = session_id();
            $user->save();

            // reset the failed login counter for that user (if necessary)
            if($user->user_last_failed_login > 0) {
                $this->resetFailedLoginCounterOfUser($user->user_email);
            }
            $this->resetUserNotFoundCounter();

            return true;
        }
        $this->incrementFailedLoginCounterOfUser($user->user_email);
        return false;
    }

    /**
     * Increment the failed-login-count by 1.
     * Add timestamp to last-failed-login.
     */
    private function incrementUserNotFoundCounter()
    {
        // Username enumeration prevention: set session failed login count and last failed login for users not found
        if(!isset($_SESSION['failed-login-count'])){
            $_SESSION['failed-login-count'] = 1;
            $_SESSION['last-failed-login'] = time();
        }else{
            $_SESSION['failed-login-count'] = $_SESSION['failed-login-count'] + 1;
            $_SESSION['last-failed-login'] = time();
        }
    }

    /**
     * Increments the failed-login counter of a user
     *
     * @param $user_email
     */
    public function incrementFailedLoginCounterOfUser($user_email)
    {
        $user = User::where('user_email', $user_email)->first();
        $user->user_failed_logins = $user->user_failed_logins+1;
        $user->user_last_failed_login = time();
        $user->save();
    }

    /**
     * Reset the failed-login-count to 0.
     * Reset the last-failed-login to an empty string.
     */
    private function resetUserNotFoundCounter()
    {
        $_SESSION['failed-login-count'] = 0;
        $_SESSION['last-failed-login'] = '';
    }

    /**
     * Resets the failed-login counter of a user back to 0
     *
     * @param $user_email
     */
    public static function resetFailedLoginCounterOfUser($user_email)
    {
        $user = User::where('user_email', $user_email)->first();
        $user->user_failed_logins = 0;
        $user->user_last_failed_login = NULL;
        $user->save();
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