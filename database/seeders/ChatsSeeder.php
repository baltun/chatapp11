<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chat1 = Chat::create([
            'slug' => 'chat1',
        ]);
        $chat1->participants()->attach([
            User::find(1),
            User::find(2),
        ]);
        $chat2 = Chat::create([
            'slug' => 'chat1',
        ]);
        $chat2->participants()->attach([
            User::find(3),
            User::find(4),
        ]);
    }
}
