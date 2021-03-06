<?php

namespace kalanis\kw_input;


use ArrayAccess, IteratorAggregate, Traversable, Countable, ArrayIterator;


/**
 * Class Input
 * @package kalanis\kw_input
 * Abstraction of inputs - this is access which can be implemented without the whole bloat of kw_input
 * but still passed into processing libraries
 */
class Input implements ArrayAccess, IteratorAggregate, Countable
{
    /** @var Interfaces\IEntry[] */
    protected $inputs = [];

    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    public final function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    public final function __set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    public final function __isset($offset)
    {
        return $this->offsetExists($offset);
    }

    public final function __unset($offset)
    {
        $this->offsetUnset($offset);
    }

    /**
     * Implementing ArrayAccess
     * @param string|int|null $offset
     * @param Interfaces\IEntry $value
     */
    public final function offsetSet($offset, $value): void
    {
        if ($value instanceof Interfaces\IEntry) {
            $this->inputs[$offset] = $value;
        }
    }

    /**
     * Implementing ArrayAccess
     * @param string|int $offset
     * @return bool
     */
    public final function offsetExists($offset): bool
    {
        return isset($this->inputs[$offset]);
    }

    /**
     * Implementing ArrayAccess
     * @param string|int $offset
     */
    public final function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->inputs[$offset]);
        }
    }

    /**
     * Implementing ArrayAccess
     * @param string|int $offset
     * @return Interfaces\IEntry|null
     */
    public final function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->inputs[$offset] : null;
    }

    /**
     * Implementing IteratorAggregate
     * Return all inputs as array iterator
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->inputs);
    }

    /**
     * Implementing Countable
     * @return int
     */
    public final function count(): int
    {
        return count($this->inputs);
    }
}