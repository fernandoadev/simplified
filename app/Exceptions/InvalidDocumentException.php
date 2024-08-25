<?php

namespace App\Exceptions;

use RuntimeException;


class InvalidDocumentException extends RuntimeException
{
    /**
     * @param string $document
     * 
     * @return InvalidDocumentException
     */
    public static function withDocument(string $document): self
    {
        return new self(sprintf('Invalid document: %s', $document));
    }
}
