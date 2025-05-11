<?php

namespace App\DTO;

use Illuminate\Http\Request;

abstract class Dto
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromRequest(Request $request)
    {
        $requestBodyAndQuery = $request->all();
        $requestRoute = $request->route()->parameters();
        $requestAll = array_merge($requestBodyAndQuery, $requestRoute);
        $dto = new static($requestAll);

        return $dto;
    }
}
