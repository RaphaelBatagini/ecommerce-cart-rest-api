<?php

namespace App\Filters;

use FilterIterator;

class ProductFilter extends FilterIterator
{
    private $productFilter;

    public function __construct(\Iterator $iterator, $filter)
    {
        parent::__construct($iterator);
        $this->productFilter = $filter;
    }

    public function accept()
    {
        $product = $this->getInnerIterator()->current();
        return $product->isGift() === $this->productFilter;
    }
}
