<?php

namespace App\Services;

use App\DTO\ChatDTO;
use App\Models\Chat;
use App\Models\ChatsUsers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ChatsService
{

    public function createOrGet(ChatDTO $chatDto)
    {
        $chat = null;
        $existingChat = $this->isChatExists($chatDto->userIds);
        if ($existingChat) {
            $chat = $existingChat;
        } else {
            $createdChat = $this->create($chatDto->userIds);
            $chat = $createdChat;
        }

        return $chat->id;
    }

    public function isChatExists(
        array $userIds
    ): ?Chat
    {
        // находим чаты, в которых есть указанные пользователи
        $existingChat = Chat::query()
            ->whereIn('id', function ($query) use ($userIds) {
                $query->select('chat_id')
                    ->from('chats_users')
                    ->whereIn('user_id', $userIds)
                    ->groupBy('chat_id')
                    ->havingRaw('COUNT(*) = 2');
            })
            // и в которых нет никаких других пользователей
            ->whereNotIn('id', function ($query) use ($userIds) {
                $query->select('chat_id')
                    ->from('chats_users')
                    ->whereNotIn('user_id', $userIds);
            })
            ->first(['id', 'slug']);
        dd($existingChat);
        return $existingChat;
    }

    public function create(array $userIds, $slug = null)
    {
        $chat = null;
        DB::transaction(function () use ($userIds, $slug, &$chat) {

            $chat = Chat::create();

            foreach ($userIds as $userId) {
                ChatsUsers::create([
                    'chat_id' => $chat->id,
                    'user_id' => $userId,
                ]);
            }
        });

        return $chat;
    }

    public function listForUser($user)
    {
        $chats = Chat::whereHas('chatUsers', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('chatUsers')
            ->get();

        return $chats;
    }
}
