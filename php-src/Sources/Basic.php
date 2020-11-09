<?php

namespace kalanis\kw_input\Sources;


/**
 * Class Basic
 * @package kalanis\kw_input\Sources
 * Source of values to parse and use
 * @codeCoverageIgnore because this is access to php internals
 */
class Basic implements ISource
{
    public function &get(): ?array
    {
        return $_GET;
    }

    public function &post(): ?array
    {
        return $_POST;
    }

    public function &files(): ?array
    {
        return $_FILES;
    }

    public function &session(): ?array
    {
        return $_SESSION;
    }

    public function &server(): ?array
    {
        return $_SERVER;
    }

    public function &env(): ?array
    {
        return $_ENV;
    }
}
