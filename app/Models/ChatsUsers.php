<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatsUsers extends Model
{
    public $table = 'chats_users';

    public $fillable = [
        'chat_id',
        'user_id',
    ];
}
