<?php

namespace App\Http\Controllers;

use App\Services\Users\DTO\UsersListDTO;
use App\Services\Users\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Knuckles\Camel\Extraction\ResponseCollection;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function __construct(protected UsersService $usersService)
    {

    }

    #[Authenticated]
    #[Group('Users')]
//    #[ResponseFromApiResource(JsonResponse::class, Chat::class, status: StatusCode::HTTP_OK)]
    public function list(Request $request)
    {
        $usersListDto = new UsersListDTO(
            $request['per_page'] ?? 20,
            $request['page'] ?? 1,
        );
//        $perPage = $request['per_page'] ?? 20;
//        $page = $request['page'] ?? 1;

        $users = $this->usersService->get($usersListDto);

        return (new ResponseCollection($users))->response()
            ->setStatusCode(Response::HTTP_OK);
//        return response()
//            ->json(new ResourceCollection($users), Response::HTTP_OK)
//            ->header('ololo', '25')
//            ;
    }
}
