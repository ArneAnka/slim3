<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'note_text',
        'user_id'
    ];
    
}
