<?php

namespace App\Services\Messages\DTO;

use App\DTO\Dto;

class MessageListDto extends Dto
{
    public $user;
    public $chat;
    public $pageNumber = 1;
    public $perPage = 20;
    public $columns = ['id', 'text', 'author_id', 'created_at'];
    public $searchText = '';
}
