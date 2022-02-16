<?php

namespace App\Services;

use App\Exceptions\ProductException;
use App\Helpers\JsonConsumer;
use stdClass;
use App\Services\ProductDiscount;

class Product
{
    private $products;

    public function __construct()
    {
        $jsonConsumer = new JsonConsumer(
            resource_path() . '/jsons/products.json'
        );

        $this->products = $jsonConsumer->getFileData();

        $this->productDiscountService = new ProductDiscount();
    }

    public function get(int $id): bool|stdClass
    {
        $products = array_filter(
            $this->products,
            function ($product) use ($id) {
                return $product->id === $id;
            }
        );

        if (empty($products)) {
            return false;
        }

        return array_shift($products);
    }

    public function find(array $params): array
    {
        $products = array_filter(
            $this->products,
            function ($product) use ($params) {
                return !array_diff_assoc($params, (array) $product);
            }
        );

        if (empty($products)) {
            return [];
        }

        return $products;
    }

    public function calculateValues(stdClass $product): stdClass
    {
        if (
            empty($product->amount)
            || empty($product->quantity)
        ) {
            throw new ProductException(
                'Não foi possível calcular os valores do produtos'
            );
        }

        $discount = $this->productDiscountService
            ->getProductDiscount($product->id);

        if ($product->is_gift) {
            $product->amount = 0;
        }

        $product->unit_amount = $product->amount;
        $product->total_amount = $product->amount * $product->quantity;
        $product->discount = floor($product->total_amount * $discount);
        unset($product->amount);

        return $product;
    }
}
