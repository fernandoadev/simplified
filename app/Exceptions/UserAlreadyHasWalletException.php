<?php

namespace App\Exceptions;

use RuntimeException;


class UserAlreadyHasWalletException extends RuntimeException
{
    /**
     * @param string $id
     * 
     * @return UserAlreadyHasWalletException
     */
    public static function withId(string $id): self
    {
        return new self(sprintf('User already has wallet with id: %s', $id));
    }
}
