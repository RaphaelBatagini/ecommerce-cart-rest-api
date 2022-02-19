<?php

use App\DTO\ProductDTO;

class CartTest extends TestCase
{
    private $cartPayload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartPayload = [
            'products' => [
                [
                    'id' => 2,
                    'quantity' => 2
                ]
            ]
        ];
    }

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
            $this->cartPayload
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
        // Mock ProductService::get to always return product with isGift true
        $productDTOMock = new ProductDTO(
            $this->cartPayload['products'][0]
        );
        $productDTOMock->setIsGift(true);

        $productService = Mockery::mock('App\Services\ProductService');
        $productService->shouldReceive('get')
            ->once()
            ->andReturn($productDTOMock);

        $this->app->instance('App\Services\ProductService', $productService);
        // End Mock

        $this->json(
            'POST',
            '/cart/add',
            $this->cartPayload
        );

        $this->assertEquals(422, $this->response->getStatusCode());
    }
}
