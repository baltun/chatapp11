<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersListRequest;
use App\Models\User;
use App\Services\Users\DTO\UsersListDTO;
use App\Services\Users\UsersService;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;

class UsersController extends Controller
{
    public function __construct(protected UsersService $usersService)
    {

    }

    #[Authenticated]
    #[Group('Users')]
//    #[ResponseFromApiResource(JsonResponse::class, Chat::class, status: StatusCode::HTTP_OK)]
    public function index(UsersListRequest $request)
    {
        $usersListDto = new UsersListDTO(
            $request->validated()
        );

        $users = $this->usersService->list($usersListDto);

        return JsonResource::collection($users)
            ->additional([]);
    }
}
