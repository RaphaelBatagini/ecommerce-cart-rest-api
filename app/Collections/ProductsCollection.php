<?php

namespace App\Collections;

use App\Collections\Collection;

class ProductsCollection extends Collection
{
    public function paginate($offset = 0)
    {
        $iterator = parent::paginate($offset);

        return array_map(function ($item) {
            return $item->toArray();
        }, iterator_to_array($iterator));
    }
}
