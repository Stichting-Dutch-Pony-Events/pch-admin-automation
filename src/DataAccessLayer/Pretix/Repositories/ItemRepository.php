<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\Views\Item;

class ItemRepository extends PretixBaseRepository
{
    public function getItemById(int $id): Item
    {
        return new Item($this->pretixApiClient->retrieve('items/'.$id));
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        $items   = $this->pretixApiClient->retrieveAll('items');
        $itemObj = [];
        foreach ($items as $item) {
            $itemObj[] = new Item($item);
        }
        return $itemObj;
    }
}
