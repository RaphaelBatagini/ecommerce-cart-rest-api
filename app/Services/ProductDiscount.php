<?php

namespace App\Services;

use App\Exceptions\GrpcException;
use Discount\DiscountClient;
use Discount\GetDiscountRequest;
use Grpc\ChannelCredentials;

class ProductDiscount
{
    /** @var  DiscountClient */
    private $client;

    public function __construct()
    {
        $this->client = new DiscountClient(
            $_ENV['GRPC_DISCOUNT_SERVICE_HOST'],
            [
                'credentials' => ChannelCredentials::createInsecure()
            ]
        );
    }

    /**
     * @throws GrpcException
     */
    public function getProductDiscount(int $productId): float
    {
        try {
            $getDiscountRequest = new GetDiscountRequest();
            $getDiscountRequest->setProductID($productId);
            $response = $this->client
                ->GetDiscount($getDiscountRequest)
                ->wait();

            $this->handleErrorResponse($response);

            return $response[0]->getPercentage();
        } catch (GrpcException $e) {
            return 0;
        }
    }

    private function handleErrorResponse(array $response): void
    {
        if ($response[1]->code !== 0) {
            throw new GrpcException(
                sprintf(
                    'gRPC request failed : error code: %s, details: %s',
                    $response[1]->code,
                    $response[1]->details
                )
            );
        }
    }
}
