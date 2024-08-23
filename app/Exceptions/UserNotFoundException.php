<?php

namespace App\Exceptions;

use RuntimeException;
use App\Exceptions\ExceptionsInterface;


class UserNotFoundException extends RuntimeException implements ExceptionsInterface
{
    /**
     * @param string $id
     * 
     * @return UserNotFoundException
     */
    public static function withId(string $id): self
    {
        return new self(sprintf('Role not found with id: %s', $id));
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'User not found.';
    }
}
