<?php

namespace App\DTO\Users;

class UpdateUserDTO
{
    public function __construct(
        readonly public string $id,
        readonly public string $name,
        readonly public ?string $password = null,
    ) {

    }
}
