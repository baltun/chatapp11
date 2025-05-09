<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    const MESSAGE_TEXT_1 = 'Hello, how are you?';
    const MESSAGE_TEXT_2 = 'I am fine, thank you!';
    const MESSAGE_TEXT_3 = 'What are you doing?';
    const MESSAGE_TEXT_4 = 'Just working on a project.';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chat1 = Chat::find(1);
        $chat2 = Chat::find(2);

        $message1_1 = $chat1->messages()->create([
            'author_id' => 1,
            'text' => self::MESSAGE_TEXT_1,
        ]);

        $message1_2 = $chat1->messages()->create([
            'author_id' => 2,
            'text' => self::MESSAGE_TEXT_2,
        ]);

        $message2_1 = $chat2->messages()->create([
            'author_id' => 3,
            'text' => self::MESSAGE_TEXT_3,
        ]);

        $message2_2 = $chat2->messages()->create([
            'author_id' => 4,
            'text' => self::MESSAGE_TEXT_4,
        ]);
    }
}
