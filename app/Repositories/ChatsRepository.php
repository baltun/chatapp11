<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\ChatsUsers;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ChatsRepository implements ChatsRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected Chat $chat)
    {
        //
    }

    public function createOrGet($chatDto)
    {
        $existingChat = $this->getByUsers([$chatDto->user1, $chatDto->user2]);
        if ($existingChat) {
            return $existingChat->id;
        }

        $this->create([$chatDto->user1, $chatDto->user2]);

        return $this->chat->id;
    }

    public function getByUsers(
        array $userIds
    )
    {
        // находим чаты, в которых есть указанные пользователи
        $existingChat = Chat::whereIn('id', function (Builder $query) use ($userIds) {
            $query->select('chat_id')
                ->from('chats_users')
                ->whereIn('user_id', $userIds)
                ->groupBy('chat_id')
                ->havingRaw('COUNT(*) = 2');
        })
        // и в которых нет никаких других пользователей
            ->whereNotIn('id', function (Builder $query) use ($userIds) {
                $query->select('chat_id')
                    ->from('chats_users')
                    ->whereNotIn('user_id', $userIds);
            })
            ->first(['id', 'slug']);

        return $existingChat;
    }

    public function create(array $userIds, $slug = null)
    {
        $chat = null;
        DB::transaction(function () use ($userIds, $slug, &$chat) {

//            $chat = Chat::create(['slug' => $slug]);
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
}
