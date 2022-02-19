<?php

namespace App\Collections;

use App\Filters\GiftProductFilter;

class GiftProductsCollection extends ProductsCollection
{
    public function getIterator(): GiftProductFilter
    {
        return new GiftProductFilter(parent::getIterator(), true);
    }

    public function count()
    {
        return iterator_count($this->getIterator());
    }
}
