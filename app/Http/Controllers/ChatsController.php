<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatCreateRequest;
use App\Http\Resources\v1\Chat\ChatResource;
use App\Models\Chat;
use App\Services\Chats\DTO\ChatCreateDTO;
use App\Models\User;
use App\Services\Chats\ChatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class ChatsController extends Controller
{
    public function __construct(public ChatsService $chatsService)
    {

    }

    #[Authenticated]
    #[Group('Chats')]
    #[UrlParam('user', type: 'string', description: "Must be a user ID")]
//    #[ResponseFromApiResource(JsonResource::class, Chat::class, status: StatusCode::HTTP_OK)]
    public function index(User $user): JsonResponse
    {
        $chats = $this->chatsService->listForUser($user);

        return (new JsonResource($chats))->response();
    }

    #[Authenticated]
    #[Group('Chats')]
    #[UrlParam('user', type: 'string', description: "Must be a user ID")]
    #[BodyParam('userIds', type: 'integer[]', description: "Must be an array of user IDs")]
    #[BodyParam('slug', type: 'string', description: "Must be a chat slug")]
//    #[ResponseFromApiResource(JsonResponse::class, Chat::class, status: StatusCode::HTTP_CREATED)]
    #[Response(['message' => 'Chat must have at least 2 users'], StatusCode::HTTP_BAD_REQUEST, description: 'Business Logic errors')]
    #[Response(['message' => 'Slug is required'], StatusCode::HTTP_BAD_REQUEST, description: 'Business Logic errors')]
    #[Response(['message' => 'Failed to create chat'], StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Business Logic errors')]
    public function createOrGet(ChatCreateRequest $request, User $user): JsonResponse
    {
        $chatCreateDTO = new ChatCreateDTO($request->validated());
        $chat = $this->chatsService->createOrGet($chatCreateDTO);

        return (new JsonResource($chat))->response();
    }

    #[Authenticated]
    #[Group('Chats')]
    #[UrlParam('user', type: 'string', description: "Must be a user ID")]
    #[UrlParam('chat', type: 'int', description: "Must be a chat ID")]
//    #[ResponseFromApiResource(JsonResponse::class, Chat::class, status: StatusCode::HTTP_NO_CONTENT)]
    #[Response(['message' => 'There is no chat with this id'], StatusCode::HTTP_NOT_FOUND, description: 'Business Logic errors')]
    public function destroy(User $user, Chat $chat): JsonResponse
    {
        $this->chatsService->delete($chat->id);

        return response()->json(['message' => 'Chat deleted successfully'], StatusCode::HTTP_NO_CONTENT);
    }
}
