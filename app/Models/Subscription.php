<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	public function user(){
		$this->belongsTo('App\User');
	}
}
