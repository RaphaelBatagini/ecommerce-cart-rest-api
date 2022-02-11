<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductDiscount;

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
        if (!$request->isJson()) {
            throw new \Exception("Payload should be a JSON", 422);
        }

        $params = $request->json()->all();

        if (empty($params['products'])) {
            throw new \Exception("Payload should have 'products' index", 422);
        }

        foreach ($params['products'] as &$product) {
            $product['discount'] = $this->productDiscountService
                ->getProductDiscount($product['id']);
        }

        return response($params['products'], 200);
    }
}
