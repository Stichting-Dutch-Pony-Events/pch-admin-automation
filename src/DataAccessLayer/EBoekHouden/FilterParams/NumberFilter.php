<?php

namespace App\DataAccessLayer\EBoekHouden\FilterParams;

use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\NumberFilterType;

class NumberFilter implements FilterParamInterface
{
    public function __construct(
        public string           $field,
        public ?int             $value,
        public NumberFilterType $type = NumberFilterType::EQUAL,
        public ?int             $min = null,
        public ?int             $max = null
    ) {
    }

    private function getNumberStr(): string
    {
        if ($this->type === NumberFilterType::RANGE && $this->min !== null && $this->max !== null) {
            return sprintf('%d,%d', $this->min, $this->max);
        }

        if ($this->value === null) {
            return '';
        }

        return (string)$this->value;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s=%s',
            $this->field,
            $this->type->value,
            $this->getNumberStr()
        );
    }
}
