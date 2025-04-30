<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatCreateRequest;
use App\Models\Chat;
use App\Services\Chats\DTO\ChatCreateDTO;
use App\Models\User;
use App\Services\Chats\ChatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class ChatsController extends Controller
{
    public function __construct(public ChatsService $chatsService)
    {

    }
    public function index(User $user): JsonResponse
    {
        $chats = $this->chatsService->listForUser($user);

        return (new JsonResource($chats))->response();
    }

    public function createOrGet(ChatCreateRequest $request): JsonResponse
    {
        $chatCreateDTO = new ChatCreateDTO($request->validated());
        $chat = $this->chatsService->createOrGet($chatCreateDTO);

        return (new JsonResource($chat))->response();
    }

    public function destroy($chat): JsonResponse
    {
        $this->chatsService->delete($chat);

        return response()->json(['message' => 'Chat deleted successfully'], StatusCode::HTTP_NO_CONTENT);
    }
}
