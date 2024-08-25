<?php

namespace App\Exceptions;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    /**
     * @param string $id
     *
     * @return UserNotFoundException
     */
    public static function withId(string $id): self
    {
        return new self(sprintf('User not found with id: %s', $id));
    }
}
