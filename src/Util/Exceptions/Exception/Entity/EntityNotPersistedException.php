<?php

namespace App\Util\Exceptions\Exception\Entity;

use Exception;

class EntityNotPersistedException extends Exception
{
    public function __construct(string $message = 'Entity not persisted', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}