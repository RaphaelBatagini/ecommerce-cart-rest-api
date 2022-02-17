<?php

namespace App\DTO;

class ProductDTO
{
    private $product;

    public function __construct($product)
    {
        $this->product = (array) $product;
    }

    public function __call($name, $arguments)
    {
        $attributeName = self::getAttributeName($name);

        if (strpos($name, 'get') !== false) {
            return $this->product[$attributeName];
        }

        if (strpos($name, 'set') !== false) {
            return $this->product[$attributeName] = $arguments;
        }
    }

    public function toArray()
    {
        return $this->product;
    }

    private static function getAttributeName($methodName)
    {
        $attr = lcfirst(str_replace(['get', 'set'], '', $methodName));
        return ltrim(
            strtolower(
                preg_replace('/[A-Z]/', '_$0', $attr)
            ),
            '_'
        );
    }
}
