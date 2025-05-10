<?php

namespace App\Repositories;

use App\Services\Users\DTO\UsersListDTO;
use App\Models\User;

class UsersRepository implements UsersRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function get(UsersListDTO $usersDTO)
    {
        $users = User::paginate($usersDTO->perPage);

        return $users;
    }
}
