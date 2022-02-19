<?php

class CartTest extends TestCase
{
    /**
     * Test if cart contents keep returning even if the API can't reach the
     * gRPC service
     *
     * @return void
     */
    public function testCartDoesntBreakWhenDiscountServiceIsOffline()
    {
        $_ENV['GRPC_DISCOUNT_SERVICE_HOST'] = 'fake_grpc_service:50051';

        $this->json(
            'POST',
            '/cart/add',
            [
                'products' => [
                    [
                        'id' => 2,
                        'quantity' => 2
                    ]
                ]
            ]
        );

        $response = json_decode($this->response->getContent());

        $this->assertEquals(0, $response->total_discount);
        $this->assertEquals(0, $response->products[0]->discount);
    }

    /**
     * Test if cart doesnt accept gift product on add product to card endpoint
     * payload
     *
     * @return void
     */
    public function testCartDoesntAcceptGiftProduct()
    {
        $this->json(
            'POST',
            '/cart/add',
            [
                'products' => [
                    [
                        'id' => 6, // 6 is a gift product
                        'quantity' => 2
                    ]
                ]
            ]
        );

        $this->assertEquals(422, $this->response->getStatusCode());
    }
}
