<?php

namespace App\Services\Chats\DTO;

use App\DTO\Dto;

class ChatCreateDTO extends Dto
{
    public string $slug = '';
    public array $userIds = [];
    public array $options = [];
}
