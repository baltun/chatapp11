<?php

namespace App\Http\Controllers;

use App\Services\UsersService;
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
    public function list($page = 0)
    {
        $users = $this->usersService->get(20, $page);

//        return new ResourceCollection($users);
        return response()
            ->json(new ResourceCollection($users), Response::HTTP_OK)
            ->header('ololo', '25')
            ;
    }
}
