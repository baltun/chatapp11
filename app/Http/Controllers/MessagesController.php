<?php

namespace App\Http\Controllers;

use App\Services\Messages\DTO\MessageCreateDto;
use App\Http\Requests\MessageCreateRequest;
use App\Services\Messages\MessagesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class MessagesController extends Controller
{
    public function __construct(
        protected MessagesService $messagesService
    )
    {
    }

    #[Authenticated]
    #[Group('Messages')]
    #[UrlParam('user', type: 'string', description: "Must be a user ID")]
    #[UrlParam('chat', type: 'int', description: "Must be a chat ID")]
    #[BodyParam('text', type: 'string', description: "Must be a message text")]

//    #[ResponseFromApiResource(JsonResponse::class, Message::class, status: StatusCode::HTTP_NO_CONTENT)]
    public function create(MessageCreateRequest $request, $user, $chatId): JsonResponse
    {
        $messageCreateDto = new MessageCreateDto($request->validated());
        $message = $this->messagesService->create($messageCreateDto, $user, $chatId);

        return (new JsonResource($message))->response()
            ->setStatusCode(StatusCode::HTTP_CREATED);
    }

    #[Authenticated]
    #[Group('Messages')]
    #[UrlParam('user', type: 'string', description: "Must be a user ID")]
    #[UrlParam('chat', type: 'int', description: "Must be a chat ID")]
//    #[ResponseFromApiResource(JsonResponse::class, Message::class, status: StatusCode::HTTP_NO_CONTENT)]
    public function list($user, $chat)
    {
        $messages = $this->messagesService->list($user, $chat);

        return (new ResourceCollection($messages))->response()
            ->setStatusCode(StatusCode::HTTP_OK);
    }
}
