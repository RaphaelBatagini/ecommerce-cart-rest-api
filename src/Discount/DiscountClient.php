<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Discount;

/**
 * Service that return mocked discount percentage.
 */
class DiscountClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Discount\GetDiscountRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetDiscount(\Discount\GetDiscountRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/discount.Discount/GetDiscount',
        $argument,
        ['\Discount\GetDiscountResponse', 'decode'],
        $metadata, $options);
    }

}
