<?php

namespace App\Services\Users\DTO;

class UsersCreateDTO
{
    public function __construct(
        public $perPage,
        public $currentPage
    )
    {

    }
}
