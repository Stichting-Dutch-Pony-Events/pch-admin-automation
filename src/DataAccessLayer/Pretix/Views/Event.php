<?php

namespace App\DataAccessLayer\Pretix\Views;

class Event
{
    public function __construct(
        public array  $name,
        public string $slug,
        public bool   $live,
        public bool   $testmode,
        public string $currency,
    ) {
    }

    public function getName(): string
    {
        if (isset($this->name['en'])) {
            return $this->name['en'];
        }

        if (isset($this->name['nl'])) {
            return $this->name['nl'];
        }

        return 'Unknown Event';
    }
}
