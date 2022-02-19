<?php

namespace App\Http\Controllers;

use App\DTO\CartDTO;
use Illuminate\Http\Request;
use App\Exceptions\ProductException;
use App\Services\ProductService;

class CartController extends Controller
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartDTO();
    }

    public function addProducts(Request $request)
    {
        try {
            $this->validate($request, [
                'products' => 'required',
                'products.*.id' => 'required|integer',
                'products.*.quantity' => 'required|integer',
            ]);

            $params = $request->json()->all();

            foreach ($params['products'] as $product) {
                $this->cart->addProduct(
                    $this->getProductData($product['id']),
                    $product['quantity']
                );
            }

            $this->addGiftProduct();

            return response($this->cart->getData(), 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }
    }

    private function getProductData($productId)
    {
        $productService = new ProductService();
        $product = $productService->get($productId);

        if (!$product || $product->getIsGift()) {
            throw new ProductException(
                "Produto de ID {$productId} não encontrado ou não permitido"
            );
        }

        return $product;
    }

    private function addGiftProduct(): void
    {
        if (!$this->isBlackFriday()) {
            return;
        }

        $productService = new ProductService();
        $giftProductsCollection = $productService->getAllGiftProducts();

        if (empty($giftProductsCollection)) {
            return;
        }

        $giftProduct = array_shift($giftProductsCollection);

        $this->cart->addProduct($giftProduct, 1);
    }

    private function isBlackFriday()
    {
        $currentDate = date('Y/m/d');
        $blackFridayDate = $_ENV['BLACKFRIDAY_DATE'] ?? '';

        return $currentDate === $blackFridayDate;
    }
}
