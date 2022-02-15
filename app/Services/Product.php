<?php

namespace App\Services;

use App\Helpers\JsonConsumer;

class Product
{
    private $products;

    public function __construct()
    {
        $jsonConsumer = new JsonConsumer(
            resource_path() . '/jsons/products.json'
        );

        $this->products = $jsonConsumer->getFileData();
    }

    public function get($id)
    {
        $product = array_filter(
            $this->products,
            function ($product) use ($id) {
                return $product->id === $id;
            }
        );

        if (empty($product)) {
            return false;
        }

        return $product[0];
    }
}
