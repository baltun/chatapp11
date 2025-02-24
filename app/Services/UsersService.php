<?php

namespace App\Services;

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

    public function get($perPage, $currentPage)
    {
        $users = $this->usersRepository->get($perPage, $currentPage);

        return $users;
    }
}
