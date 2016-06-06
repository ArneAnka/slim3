<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_email',
        'user_name',
        'user_password_hash',
    ];

    public function setPassword($password)
    {
        $this->update([
            'user_password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
