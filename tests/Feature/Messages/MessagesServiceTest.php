<?php

namespace Tests\Feature\Messages;

use App\Exceptions\AppLogicException;
use App\Models\Chat;
use App\Models\User;
use App\Services\Messages\DTO\MessageCreateDto;
use App\Models\Message;
use App\Services\Messages\DTO\MessageListDto;
use App\Services\Messages\MessagesService;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\MessagesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesServiceTest extends TestCase
{
    use RefreshDatabase;

    public $startMessageTest;

    const TEST_MESSAGE_TEXT_1 = 'this test message text';

    public function setUp(): void
    {
        $this->startMessageTest = microtime(true);

        parent::setUp();
        $this->seed([
            UsersSeeder::class,
            ChatsSeeder::class,
            MessagesSeeder::class,
        ]);

        $this->actingAs(User::find(1));

    }

    public function test_message_create(): void
    {
        $chat = Chat::find(1);
        $user =$chat->participants()->first();

        $dto = new MessageCreateDto([
            'text' => self::TEST_MESSAGE_TEXT_1,
        ]);

        $messagesService = $this->app->make(MessagesService::class);
        $message = $messagesService->store($dto, $user->id, $chat->id);

        $this->assertNotEmpty($message);
        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($message->text, self::TEST_MESSAGE_TEXT_1);
    }

    public function test_messages_list_success()
    {
        $messagesService = $this->app->make(MessagesService::class);


        $messageListDto = new MessageListDto([
            'pageNumber' => 1,
            'perPage' => 20,
            'chat' => 1,
            'user' => 1,
        ]);

        $messages = $messagesService->list($messageListDto);

        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(Message::class, $messages[0]);
        $this->assertEquals($messages[0]->text, MessagesSeeder::MESSAGE_TEXT_1);
        $this->assertEquals($messages[0]->author_id, 1);
        $this->assertInstanceOf(User::class, $messages[0]->author);
        $this->assertCount(20, $messages);
    }
    public function test_messages_list_fail_not_participant()
    {
        $messagesService = $this->app->make(MessagesService::class);

        $messageListDto = new MessageListDto([
            'pageNumber' => 1,
            'perPage' => 20,
            'chat' => 2,
            'user' => 1,
        ]);

        $this->expectException(AppLogicException::class);
        $this->expectExceptionMessage('User is not a participant of the chat');
        $messagesService->list($messageListDto);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $endMessageTest = microtime(true);
        $duration = $endMessageTest - $this->startMessageTest;
        echo "время:" . $duration;

    }
}
