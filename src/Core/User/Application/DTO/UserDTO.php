<?php

declare(strict_types=1);

namespace App\Core\User\Application\DTO;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        public bool $isActive
    ) {}
}
