<?php

namespace App\Repositories;

use App\Services\Users\DTO\UsersListDTO;

interface UsersRepositoryInterface
{
    public function get(UsersListDTO $usersDTO);
}
