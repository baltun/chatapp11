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
        ])->users()->attach([
            User::find(1),
            User::find(2),
        ]);
    }
}
