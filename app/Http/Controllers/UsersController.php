<?php

namespace App\Http\Controllers;

use App\Services\Users\DTO\UsersDTO;
use App\Services\Users\UsersService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function __construct(protected UsersService $usersService)
    {

    }
    public function list(Request $request)
    {
        $usersDto = new UsersDTO(
            $request['per_page'] ?? 20,
            $request['page'] ?? 1,
        );
//        $perPage = $request['per_page'] ?? 20;
//        $page = $request['page'] ?? 1;

        $users = $this->usersService->get($usersDto);

        return new ResourceCollection($users);
//        return response()
//            ->json(new ResourceCollection($users), Response::HTTP_OK)
//            ->header('ololo', '25')
//            ;
    }
}
