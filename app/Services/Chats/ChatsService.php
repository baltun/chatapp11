<?php

namespace App\Services\Chats;

use App\Exceptions\AppLogicException;
use App\Services\Chats\DTO\ChatCreateDTO;
use App\Models\Chat;
use App\Models\ChatsUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatsService
{
    const SLUG_FOR_TWO_USERS_CHAT = 'personal';

    public function createOrGet(ChatCreateDTO $chatCreateDTO)
    {
        $slug = $chatCreateDTO->slug;

        if (count($chatCreateDTO->userIds) == 0) {
            throw new AppLogicException('Chat must have at least 2 participants', Response::HTTP_BAD_REQUEST);
        }

        if (count($chatCreateDTO->userIds) == 1) {
            if ($chatCreateDTO->userIds[0] == Auth::user()->id) {
                throw new AppLogicException("You can not create a chat without participants", Response::HTTP_BAD_REQUEST);
            }
            $chatCreateDTO->userIds[] = Auth::user()->id;
        }

        if (count($chatCreateDTO->userIds) == 2) {
            $chatCreateDTO->slug = self::SLUG_FOR_TWO_USERS_CHAT;
        }
        if (count($chatCreateDTO->userIds) > 2 && empty($chatCreateDTO->slug)) {
            throw new AppLogicException('Slug is required', Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::user() || !in_array(Auth::user()->id, $chatCreateDTO->userIds)) {
            throw new AppLogicException("You can not create a chat where you not a participant", Response::HTTP_BAD_REQUEST);
        }

        $existingChat = $this->isChatExists($chatCreateDTO->userIds);
        if ($existingChat) {
            return $existingChat;
        }

        $createdChat = $this->create($chatCreateDTO);
        if (!$createdChat) {
            throw new AppLogicException('Failed to create chat', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $createdChat;
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
                ->whereDoesntHave('participants', function ($query) use ($userIds) {
                    $query->whereNotIn('user_id', $userIds);
                })
                ->first();
        }

        return $existingChat;
    }

    public function create(ChatCreateDTO $chatCreateDTO): Chat
    {
        $chat = null;
        DB::transaction(function () use ($chatCreateDTO, &$chat) {
            $chat = Chat::create([
                'slug' => $chatCreateDTO->slug,
            ]);
            if (!$chat) {
                throw new AppLogicException('Failed to create chat', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $chat->participants()->attach($chatCreateDTO->userIds);
        });

        return $chat;
    }

    public function listForUser(User $user)
    {
        $chats = Chat::whereHas('chatUsers', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('chatUsers')
            ->get();

        return $chats;
    }

    /*
     * удаление чата
     */
    public function delete(int $chatId): void
    {
        $chat = Chat::find($chatId);
        if (!$chat) {
            throw new AppLogicException('There is no chat with this id', Response::HTTP_NOT_FOUND);
        }
        DB::transaction(function () use ($chat) {
            ChatsUsers::where('chat_id', $chat->id)->delete();
            $chat->delete();
        });
    }
}
