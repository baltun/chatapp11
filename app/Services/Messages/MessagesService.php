<?php

namespace App\Services\Messages;

use App\Exceptions\AppLogicException;
use App\Models\Chat;
use App\Services\Messages\DTO\MessageCreateDto;

class MessagesService
{
    public function create(MessageCreateDto $messageCreateDto, $userId, $chatId)
    {
        $chat = Chat::find($chatId);

        if (!$chat) {
            throw new AppLogicException('Chat not found');
        }

        if (!$chat->participants()->where('user_id', $userId)->exists()) {
            throw new AppLogicException('User is not a participant of the chat');
        }

        $message = $chat->messages()->create([
            'text' => $messageCreateDto->text,
            'author_id' => $userId,
        ]);

        return $message;
    }

    public function list($userId, $chatId)
    {
        $messages = Chat::find($chatId)->messages()->get();

        return $messages;
    }
}
