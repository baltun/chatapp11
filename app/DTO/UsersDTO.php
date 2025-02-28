<?php

namespace App\DTO;

class UsersDTO
{
    public function __construct(
        public $perPage,
        public $currentPage
    )
    {

    }
}
