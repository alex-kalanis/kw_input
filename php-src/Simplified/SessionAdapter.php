<?php

namespace kalanis\kw_input\Simplified;


use ArrayAccess;


/**
 * Class SessionAdapter
 * @package kalanis\kw_input\Extras
 * Accessing _SESSION via ArrayAccess
 */
class SessionAdapter implements ArrayAccess
{
    use TNullBytes;

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

    public final function offsetExists($offset): bool
    {
        return isset($_SESSION[$this->removeNullBytes($offset)]);
    }

    #[\ReturnTypeWillChange]
    public final function offsetGet($offset)
    {
        return $_SESSION[$this->removeNullBytes($offset)];
    }

    public final function offsetSet($offset, $value): void
    {
        $_SESSION[$this->removeNullBytes($offset)] = $value;
    }

    public final function offsetUnset($offset): void
    {
        unset($_SESSION[$this->removeNullBytes($offset)]);
    }
}
