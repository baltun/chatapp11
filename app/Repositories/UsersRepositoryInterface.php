<?php

namespace App\Repositories;

interface UsersRepositoryInterface
{
    public function get($perPage, $currentPage);
}
