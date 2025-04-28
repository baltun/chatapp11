<?php

namespace App\Services;

use App\DTO\ChatDTO;
use App\Models\Chat;
use App\Models\ChatParticipant;
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
        $existingChat = null;
        if (count($userIds) == 2) {
            $chatIds = ChatsUsers::query()
                ->select('chat_id')
                ->whereIn('user_id', $userIds)
                ->groupBy('chat_id')
                ->havingRaw('COUNT(DISTINCT user_id) = ?', [count($userIds)])
                ->get()
                ->pluck('chat_id')
                ->toArray();
            $existingChat = Chat::query()
                ->whereIn('id', $chatIds)
                ->whereDoesntHave('users', function ($query) use ($userIds) {
                    $query->whereNotIn('user_id', $userIds);
                })
                ->first();
        }

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
