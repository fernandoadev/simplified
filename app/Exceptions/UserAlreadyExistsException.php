<?php

namespace App\Exceptions;

use RuntimeException;


class UserAlreadyExistsException extends RuntimeException
{
    /**
     * @param string $email
     * 
     * @return UserAlreadyExistsException
     */
    public static function withEmail(string $email): self
    {
        return new self(sprintf('User already existis with email: %s', $email));
    }

    /**
     * @param string $document
     * 
     * @return UserAlreadyExistsException
     */
    public static function withDocument(string $document): self
    {
        return new self(sprintf('User already existis with document: %s', $document));
    }
}
