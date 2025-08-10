<?php

namespace App\Util\Exceptions\Exception;

use Doctrine\Common\Collections\ArrayCollection;

class ParameterCollection extends ArrayCollection
{
    public function addParameter(string $field, mixed $value): self
    {
        $this->add(new Parameter($field, $value));

        return $this;
    }
}
