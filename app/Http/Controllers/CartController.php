<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductDiscount;
use App\Exceptions\ProductException;
use App\Services\Product;

class CartController extends Controller
{
    public function __construct()
    {
        $this->productDiscountService = new ProductDiscount();
    }

    public function addProduct(Request $request)
    {
        try {
            if (!$request->isJson()) {
                throw new ProductException('Payload should be a JSON');
            }

            $params = $request->json()->all();

            if (empty($params['products'])) {
                throw new ProductException(
                    'Payload should have \'products\' index'
                );
            }

            $products = $this->processProducts($params['products']);

            return response($products, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }
    }

    private function processProducts($products)
    {
        foreach ($products as &$product) {
            $product = $this->processSingleProduct($product);
        }

        return $products;
    }

    private function processSingleProduct($product)
    {
        $this->validateProduct($product);

        $productService = new Product();
        $productObject = $productService->get($product['id']);

        if (!$productObject) {
            throw new ProductException(
                "Produto de ID {$product['id']} não encontrado"
            );
        }

        $productObject->discount = $this->productDiscountService
            ->getProductDiscount($product['id']);

        return $productObject;
    }

    private function validateProduct($product)
    {
        if (empty($product['id']) || empty($product['quantity'])) {
            throw new ProductException(
                'Products should have id and quantity index'
            );
        }
    }
}
