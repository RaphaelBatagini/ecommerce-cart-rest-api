<?php

namespace App\Filters;

use FilterIterator;

class GiftProductFilter extends FilterIterator
{
    public function __construct(\Iterator $iterator)
    {
        parent::__construct($iterator);
    }

    public function accept()
    {
        $product = $this->getInnerIterator()->current();
        return $product->getIsGift();
    }
}
