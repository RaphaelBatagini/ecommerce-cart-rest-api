<?php

namespace App\DTO;

use App\DTO\ProductDTO;
use App\Services\ProductDiscountService;

class CartDTO
{
    private $products;
    private $productDiscountService;
    private $totalAmount = 0;
    private $totalAmountWithDiscount = 0;
    private $totalDiscount = 0;

    public function __construct()
    {
        $this->productDiscountService = new ProductDiscountService();
    }

    public function addProduct(ProductDTO|array $product, int $quantity): void
    {
        if (is_array($product)) {
            $product = new ProductDTO($product);
        }

        if ($product->getIsGift()) {
            $product->setAmount(0);
        }

        $discount = 0;
        if (!$product->getIsGift()) {
            $discount = $this->productDiscountService
                ->getProductDiscount($product->getId());
        }

        $productTotalAmount = $product->getAmount() * $quantity;
        $productDiscount = floor(($product->getAmount() * $quantity) * $discount);

        $this->products[] = [
            'id' => $product->getId(),
            'quantity' => $quantity,
            'unit_amount' => $product->getAmount(),
            'total_amount' => $productTotalAmount,
            'discount' => $productDiscount,
            'is_gift' => $product->getIsGift(),
        ];

        $this->totalAmount += $productTotalAmount;
        $this->totalAmountWithDiscount += $productTotalAmount - $productDiscount;
        $this->totalDiscount += $productDiscount;
    }

    public function getData(): array
    {
        return [
            'total_amount' => $this->totalAmount,
            'total_amount_with_discount' => $this->totalAmountWithDiscount,
            'total_discount' => $this->totalDiscount,
            'products' => $this->products,
        ];
    }
}
