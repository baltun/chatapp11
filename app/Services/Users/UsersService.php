<?php

namespace App\Services\Users;

use App\Services\Users\DTO\UsersDTO;
use App\Models\User;
use App\Repositories\UsersRepository;
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

    public function get(UsersDTO $usersDTO)
    {
        $users = $this->usersRepository->get($usersDTO);

        return $users;
    }
}
