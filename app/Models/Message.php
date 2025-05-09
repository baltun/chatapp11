<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $table = 'messages';

    public $fillable = [
        'text',
        'chat_id',
        'author_id',
    ];
}
