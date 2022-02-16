<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ProductException;
use App\Services\Product;

class CartController extends Controller
{
    private $products;

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

            $this->products = $this->processProducts($params['products']);
            $this->addGiftProduct();

            return response($this->products, 200);
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

        if (!$productObject || $productObject->is_gift) {
            throw new ProductException(
                "Produto de ID {$product['id']} não encontrado ou não permitido"
            );
        }

        $productObject->quantity = $product['quantity'];

        return $productService->calculateValues($productObject);
    }

    private function validateProduct($product)
    {
        if (empty($product['id']) || empty($product['quantity'])) {
            throw new ProductException(
                'Products should have id and quantity index'
            );
        }
    }

    private function addGiftProduct(): void
    {
        if (!$this->isBlackFriday()) {
            return;
        }

        $productService = new Product();
        $giftProducts = $productService->find(['is_gift' => true]);

        if (empty($giftProducts)) {
            return;
        }

        $giftProductObject = array_shift($giftProducts);
        $giftProductObject->quantity = 1;
        $giftProductObject = $productService->calculateValues(
            $giftProductObject
        );

        array_push($this->products, $giftProductObject);
    }

    private function isBlackFriday()
    {
        $currentDate = date('Y/m/d');
        $blackFridayDate = $_ENV['BLACKFRIDAY_DATE'] ?? '';

        return $currentDate === $blackFridayDate;
    }
}
