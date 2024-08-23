<?php

namespace App\Exceptions;

use RuntimeException;
use App\Exceptions\ExceptionsInterface;


class InvalidDocumentException extends RuntimeException implements ExceptionsInterface
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Invalid document.';
    }
}
