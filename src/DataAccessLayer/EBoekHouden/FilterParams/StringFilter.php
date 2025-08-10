<?php

namespace App\DataAccessLayer\EBoekHouden\FilterParams;

use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\StringFilterType;

class StringFilter implements FilterParamInterface
{
    public function __construct(
        public string           $field,
        public string           $value,
        public StringFilterType $type = StringFilterType::EQUAL
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s=%s',
            $this->field,
            $this->type->value,
            $this->value
        );
    }
}
