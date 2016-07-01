<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Auth;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_email',
        'user_name',
        'user_password_hash',
    ];

/**
* Change the password
*
* @param string $password
*
*/
    public function setPassword($password)
    {
        $this->update([
            'user_password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    /**
    * The user has one subscription.
    *
    * @return mixed
    */
    public function subscription(){
        return $this->hasOne('\App\Models\Subscription');
    }

    /**
    * Check if the user has a valid subscription
    *
    * @return bool
    */
    public function hasSubscription(){
        $dueDate = new \DateTime(User::find($_SESSION['user_id'])->subscription->due_date);
        $todaysDate = new \DateTime();

        return $todaysDate <= $dueDate;
    }
}
