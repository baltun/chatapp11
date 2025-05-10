<?php

namespace App\Services\Auth\DTO;

use App\DTO\Dto;

class RegisterDTO extends Dto
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public string $password_confirmation;
}
