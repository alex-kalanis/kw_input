<?php

namespace kalanis\kw_input\Loaders;


use kalanis\kw_input\Inputs\IEntry;


/**
 * Class Factory
 * @package kalanis\kw_input\Loaders
 * Loading factory
 */
class Factory
{
    /** @var ALoader[] */
    protected static $loaders;

    public function getLoader(string $source): ALoader
    {
        if (isset(static::$loaders[$source])) {
            return static::$loaders[$source];
        }
        $loader = $this->select($source);
        static::$loaders[$source] = $loader;
        return $loader;
    }

    protected function select(string $source): ALoader
    {
        switch ($source) {
            case IEntry::SOURCE_FILES:
                return new File();
            default:
                return new Entry();
        }
    }
}
