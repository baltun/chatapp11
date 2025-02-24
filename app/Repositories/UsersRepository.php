<?php

namespace App\Repositories;

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


    public function get($perPage, $currentPage)
    {
        $users = User::get()->paginate();

        return $users;
    }
}
