<?php

namespace App\Services\Users;

use App\Services\Users\DTO\UsersListDTO;
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

    public function get(UsersListDTO $usersDTO)
    {
        $users = $this->usersRepository->get($usersDTO);

        return $users;
    }
}
