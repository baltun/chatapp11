<?php

namespace App\Repositories;

use App\DTO\UsersDTO;

interface UsersRepositoryInterface
{
    public function get(UsersDTO $usersDTO);
}
