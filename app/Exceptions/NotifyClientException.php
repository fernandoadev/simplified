<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class NotifyClientException extends RuntimeException
{
    /**
     * @param Throwable $error
     * 
     * @return NotifyClientException
     */
    public static function fromRequest(Throwable $error): self
    {
        return new self(sprintf('Could not complete request to notify: %s', $error->getMessage()));
    }


    /**
     * @return NotifyClientException
     */
    public static function timeOut(): self
    {
        return new self(sprintf('Notify service is temporarily out of service.'));
    }
}
