<?php

namespace App\Exceptions;

use RuntimeException;
use App\Exceptions\ExceptionsInterface;


class RoleNotFoundException extends RuntimeException implements ExceptionsInterface
{
    /**
     * @param string $role
     * 
     * @return RoleNotFoundException
     */
    public static function withRole(string $role): self
    {
        return new self(sprintf('Role not found: %s', $role));
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Role not found.';
    }
}
