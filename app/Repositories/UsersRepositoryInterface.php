<?php

namespace App\Repositories;

use App\Services\Users\DTO\UsersCreateDTO;

interface UsersRepositoryInterface
{
    public function get(UsersCreateDTO $usersDTO);
}
