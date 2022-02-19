<?php

namespace App\Collections;

use App\Collections\Collection;

class ProductsCollection extends Collection
{
    public function toArray(): array
    {
        return array_values(array_map(
            function ($item) {
                return $item->toArray();
            },
            iterator_to_array($this->getIterator())
        ));
    }
}
