<?php

namespace App\Services\Users\DTO;

use App\DTO\Dto;

class UsersListDTO extends Dto
{
    public $perPage = 20;
    public $pageNumber = 1;
    public $columns = ['id', 'email', 'last_name', 'first_name'];
}
