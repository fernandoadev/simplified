<?php

namespace App\Exceptions;

use RuntimeException;

class UserHasNotEnoughtBalanceException extends RuntimeException
{
    /**
     * @param string $id
     *
     * @return UserHasNotEnoughtBalanceException
     */
    public static function withId(string $id): self
    {
        return new self(sprintf('User has not enought balance with id: %s', $id));
    }
}
