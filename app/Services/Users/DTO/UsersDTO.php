<?php

namespace App\Services\Users\DTO;

class UsersDTO
{
    public function __construct(
        public $perPage,
        public $currentPage
    )
    {

    }
}
