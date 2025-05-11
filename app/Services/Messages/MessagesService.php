<?php

namespace App\Services\Messages;

use App\Exceptions\AppLogicException;
use App\Models\Chat;
use App\Services\Messages\DTO\MessageCreateDto;
use App\Services\Messages\DTO\MessageListDto;

class MessagesService
{
    public function store(MessageCreateDto $messageCreateDto, $userId, $chatId)
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

    public function list(MessageListDto $dto)
    {
        $chat = Chat::find($dto->chat);

        if (!$chat) {
            throw new AppLogicException('Chat not found');
        }

        if (!$chat->participants()->where('user_id', $dto->user)->exists()) {
            throw new AppLogicException('User is not a participant of the chat');
        }

        $messages = $chat->messages()
            ->where('text', 'like', '%' . $dto->searchText . '%')
            ->paginate(
                perPage: $dto->perPage,
                columns: $dto->columns,
                pageName: 'page',
                page: $dto->pageNumber,
            );

        return $messages;
    }
}
