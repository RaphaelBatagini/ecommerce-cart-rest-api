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
            $this->validate($request, [
                'products' => 'required',
            ]);

            $params = $request->json()->all();

            $this->products = $this->processProducts($params['products']);
            $this->addGiftProduct();

            return response(
                $this->getResume($this->products),
                200
            );
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }
    }

    private function getResume(array $products): array
    {
        $cartResume = [
            'total_amount' => 0,
            'total_amount_with_discount' => 0,
            'total_discount' => 0,
        ];
        foreach ($products as $product) {
            $cartResume['total_amount'] += $product->total_amount;
            $cartResume['total_amount_with_discount'] += (
                $product->total_amount - $product->discount
            );
            $cartResume['total_discount'] += $product->discount;
        }

        $cartResume['products'] = $products;
        return $cartResume;
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
