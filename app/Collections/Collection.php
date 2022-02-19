<?php

namespace App\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    protected $items;
    protected $position;

    public function __construct(array $items = [])
    {
        $this->position = 0;
        $this->items = $items;
    }

    public function offsetExists($offset)
    {
        if (is_integer($offset) || is_string($offset)) {
            return array_key_exists($offset, $this->items);
        }

        return false;
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
