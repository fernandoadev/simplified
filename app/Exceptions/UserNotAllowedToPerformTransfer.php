<?php

namespace App\Exceptions;

use RuntimeException;


class UserNotAllowedToPerformTransfer extends RuntimeException
{
    /**
     * @param string $id
     * 
     * @return UserNotAllowedToPerformTransfer
     */
    public static function withId(string $id): self
    {
        return new self(sprintf('User are not allowed to perform transfer with id: %s', $id));
    }
}
