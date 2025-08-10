<?php

namespace App\Util\Exceptions\Response;

use App\Util\Exceptions\Exception\Parameter;
use JMS\Serializer\Annotation\Type;

class PublicExceptionResponse
{
    public function __construct(
        public string $message,
        /**
         * @var Parameter[] $parameters
         * @Type("array<App\Util\Exceptions\Exception\Parameter>")
         */
        public array $parameters
    ) {
    }
}
