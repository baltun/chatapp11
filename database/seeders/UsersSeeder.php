<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');
        $user1 = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => bcrypt('password'),
        ]);

        $user3 = User::factory()->create([
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => bcrypt('password'),
        ]);

        $user4 = User::factory()->create([
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => bcrypt('password'),
        ]);
    }
}
