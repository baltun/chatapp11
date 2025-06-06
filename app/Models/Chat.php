<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Chat extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'slug',
        'options'
    ];

    public function chatUsers(): hasMany
    {
        return $this->hasMany(ChatsUsers::class, 'chat_id');
    }

    public function participants(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'chats_users', 'chat_id', 'user_id')
            ->withPivot('draft_message')
            ->using(ChatsUsers::class)
            ->withTimestamps();
    }

    public function messages(): HasMany|Chat
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
}
