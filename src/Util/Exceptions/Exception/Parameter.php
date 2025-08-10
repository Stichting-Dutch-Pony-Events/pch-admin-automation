<?php

namespace App\Util\Exceptions\Exception;

class Parameter
{
    public function __construct(
        public string $field,
        public mixed $value
    ) {
    }
}
