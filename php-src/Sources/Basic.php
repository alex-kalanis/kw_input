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
    protected $cliArgs = [];
    protected $externalArgs = [];

    public function setCli(array $cliArgs = []): self
    {
        $this->cliArgs = $cliArgs;
        return $this;
    }

    public function &cli(): ?array
    {
        return $this->cliArgs;
    }

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

    public function &external(): ?array
    {
        return $this->externalArgs;
    }

    public function setExternal(array $externalArgs = []): self
    {
        $this->externalArgs = $externalArgs;
        return $this;
    }
}
