<?php

namespace App\Http\Controllers;

use App\DTO\ChatDTO;
use App\Services\ChatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct(public ChatsService $chatsService)
    {

    }
    public function index()
    {
        $chats = $this->chatsService->get();

        return $chats;
    }

    public function createOrGet(Request $request, ChatDTO $chatDTO)
    {
        $chatDTO->slug = $request['slug'];
        $chatDTO->user1 = Auth::id() ?? 1;
        $chatDTO->user2 = $request['user2'];
        $chatDTO->options = $request['options'];

        $chatId = $this->chatsService->createOrGet($chatDTO);

        return $chatId;
    }
}
