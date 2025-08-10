<?php

namespace App\DataAccessLayer\EBoekHouden\FilterParams;

use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\DateFilterType;
use DateTimeInterface;

class DateFilter implements FilterParamInterface
{
    public function __construct(
        public string             $field,
        public ?DateTimeInterface $value,
        public DateFilterType     $type = DateFilterType::EQUAL,
        public ?DateTimeInterface $start = null,
        public ?DateTimeInterface $end = null
    ) {
    }

    private function getDateStr(): string
    {
        if ($this->type === DateFilterType::RANGE && $this->start && $this->end) {
            return sprintf('%s,%s', $this->start->format('Y-m-d'), $this->end->format('Y-m-d'));
        }

        if ($this->value) {
            return $this->value->format('Y-m-d');
        }

        return '';
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s=%s',
            $this->field,
            $this->type->value,
            $this->getDateStr()
        );
    }
}
