<?php

namespace kalanis\kw_input\Sources;


/**
 * Interface ISource
 * @package kalanis\kw_input\Sources
 * Source of values to parse
 */
interface ISource
{
    public function &cli(): ?array;

    public function &get(): ?array;

    public function &post(): ?array;

    public function &files(): ?array;

    public function &session(): ?array;

    public function &server(): ?array;

    public function &env(): ?array;
}
