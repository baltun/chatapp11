<?php

namespace App\Repositories;

use App\Services\Users\DTO\UsersCreateDTO;
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


    public function get(UsersCreateDTO $usersDTO)
    {
        $users = User::paginate($usersDTO->perPage);

        return $users;
    }
}
