<?php

namespace App\Services;

use App\DTO\ChatDTO;
use App\Repositories\ChatsRepository;
use App\Repositories\ChatsRepositoryInterface;
use http\Env\Request;

class ChatsService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ChatsRepositoryInterface $chatsRepository)
    {

    }

    public function createOrGet(ChatDTO $chatDto)
    {
//        $chatId = $this->chatsRepository->createOrGet($chatDTO);

        $existingChat = $this->chatsRepository->getByUsers([$chatDto->user1, $chatDto->user2]);
        if ($existingChat) {
            return $existingChat->id;
        }
        $createdChat = $this->chatsRepository->create([$chatDto->user1, $chatDto->user2]);

        return $createdChat->id;
    }
}
