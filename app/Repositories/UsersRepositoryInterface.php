<?php

namespace App\Repositories;

use App\Services\Users\DTO\UsersDTO;

interface UsersRepositoryInterface
{
    public function get(UsersDTO $usersDTO);
}
