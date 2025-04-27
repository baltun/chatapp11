<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatsUsers extends Pivot
{
    public $table = 'chats_users';

    public $fillable = [
        'chat_id',
        'user_id',
    ];
}
