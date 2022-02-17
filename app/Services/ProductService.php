<?php

namespace App\Services;

use App\Collections\GiftProductsCollection;
use App\Collections\ProductsCollection;
use App\Exceptions\ProductException;
use App\Helpers\JsonConsumer;
use App\Services\ProductDiscountService;
use App\DTO\ProductDTO;

class ProductService
{
    private $products;

    public function __construct()
    {
        $jsonConsumer = new JsonConsumer(
            resource_path() . '/jsons/products.json'
        );

        $this->products = $jsonConsumer->getFileData();

        foreach ($this->products as &$product) {
            $product = new ProductDTO($product);
        }

        $this->productDiscountService = new ProductDiscountService();
    }

    public function getAllProducts()
    {
        return $this->getProductsCollection();
    }

    public function getAllGiftProducts()
    {
        return $this->getProductsCollection(true);
    }

    private function getProductsCollection($giftProducts = false)
    {
        if ($giftProducts) {
            return new GiftProductsCollection($this->products);
        }

        return new ProductsCollection($this->products);
    }

    public function get(int $id): bool | ProductDTO
    {
        $products = array_filter(
            $this->products,
            function ($product) use ($id) {
                return $product->getId() === $id;
            }
        );

        if (empty($products)) {
            return false;
        }

        return array_shift($products);
    }
}
