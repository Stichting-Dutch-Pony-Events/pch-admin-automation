<?php

namespace App\DataAccessLayer\Pretix\Views;

class Item
{
    public int $id;
    public int $category;
    public object $name;
    public ?object $metaData;
    public bool $admission;
    public bool $personalized;

    public function __construct($item)
    {
        $this->id = $item->id;
        $this->category = $item->category;
        $this->name = $item->name;
        $this->metaData = $item->meta_data;
        $this->admission = $item->admission;
    }

    public function getEnglishName(): string
    {
        return $this->name->en;
    }
}
