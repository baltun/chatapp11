<?php

namespace App\Services\Users;

use App\Services\Users\DTO\UsersCreateDTO;
use App\Repositories\UsersRepositoryInterface;

class UsersService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UsersRepositoryInterface $usersRepository
    )
    {
        //
    }

    public function get(UsersCreateDTO $usersDTO)
    {
        $users = $this->usersRepository->get($usersDTO);

        return $users;
    }
}
