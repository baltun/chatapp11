<?php

namespace App\Services\Users\DTO;

class UsersListDTO
{
    public function __construct(
        public $perPage,
        public $currentPage,
        public $searchText = '',
    )
    {
    }
}
