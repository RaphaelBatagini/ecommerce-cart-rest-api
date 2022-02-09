<?php

namespace Root\HashBackendChallenge\Services;

use Root\HashBackendChallenge\Exception\GrpcException;
use Discount\DiscountClient;
use Discount\GetDiscountRequest;
use Grpc\ChannelCredentials;

class ProductDiscount
{
    /** @var  DiscountClient */
    private $client;

    public function __construct(string $host)
    {
        $this->client = new DiscountClient(
            $host,
            ['credentials' => ChannelCredentials::createDefault()]
        );
    }

    /**
     * @throws GrpcException
     */
    public function getProductDiscount(int $productId): mixed
    {
        $getDiscountRequest = new GetDiscountRequest();
        $getDiscountRequest->setProductID($productId);
        $response = $this->client
            ->GetDiscount($getDiscountRequest)
            ->wait();

        $this->handleErrorResponse($response);

        return $response[0]->getMessage();
    }

    private function handleErrorResponse(array $response): void
    {
        if ($response[1]->code !== 200) {
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
