<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Chat extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'slug',
        'options'
    ];

    public function users()
    {
        return $this->hasMany(ChatsUsers::class, 'chat_id');
    }
}
