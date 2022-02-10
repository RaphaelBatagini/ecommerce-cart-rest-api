<?php

namespace Root\HashBackendChallenge\Controllers;

use Root\HashBackendChallenge\Controllers\Controller;
use Root\HashBackendChallenge\Services\ProductDiscount;

class CartController extends Controller
{
    public function addProduct($params)
    {
        $productDiscountService = new ProductDiscount(
            $_ENV['GRPC_DISCOUNT_SERVICE_HOST']
        );

        $response = $productDiscountService->getProductDiscount(
            $params['productId']
        );

        var_dump($response);
    }
}
