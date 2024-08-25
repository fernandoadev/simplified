<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class AuthorizeClientException extends RuntimeException
{
    /**
     * @param Throwable $error
     *
     * @return AuthorizeClientException
     */
    public static function fromRequest(Throwable $error): self
    {
        return new self(sprintf('Could not complete request to authorize: %s', $error->getMessage()));
    }

    /**
     * @return AuthorizeClientException
     */
    public static function notAuthorize(): self
    {
        return new self(sprintf('Authorize verification failed.'));
    }
}
