<?php

namespace App\Exceptions;

use Throwable;

interface ExceptionsInterface extends Throwable
{
    /** @return string */
    public function getTitle(): string;
}
