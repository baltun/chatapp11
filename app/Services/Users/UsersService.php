<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\Users\DTO\UsersListDTO;

class UsersService
{
    public function list(UsersListDTO $usersDTO)
    {
        $users = User::paginate($usersDTO->perPage, $usersDTO->columns, 'page', $usersDTO->pageNumber);

        return $users;
    }
}
