<?php

namespace App\Services\Auth\DTO;

use App\DTO\Dto;

class LoginDTO extends Dto
{
    public string $email;
    public string $password;
}
