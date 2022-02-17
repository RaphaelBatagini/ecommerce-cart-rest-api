<?php

namespace App\Collections;

use App\Filters\ProductFilter;

class GiftProductsCollection extends ProductsCollection
{
    public function getIterator()
    {
        return new ProductFilter(parent::getIterator(), true);
    }

    public function count()
    {
        return iterator_count($this->getIterator());
    }
}
