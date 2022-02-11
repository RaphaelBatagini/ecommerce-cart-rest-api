<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductDiscount;
use App\Exceptions\ProductException;

class CartController extends Controller
{
    public function __construct()
    {
        $this->productDiscountService = new ProductDiscount(
            $_ENV['GRPC_DISCOUNT_SERVICE_HOST']
        );
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

            foreach ($params['products'] as &$product) {
                $this->validateProduct($product);

                // TODO: Buscar infos dos produtos em arquivo JSON

                $product['discount'] = $this->productDiscountService
                    ->getProductDiscount($product['id']);
            }

            return response($params['products'], 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }
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
