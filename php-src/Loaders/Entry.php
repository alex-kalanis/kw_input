<?php

namespace kalanis\kw_input\Loaders;


use kalanis\kw_input\Inputs;


/**
 * Class Entry
 * @package kalanis\kw_input\Loaders
 * Load input arrays into normalized entries
 */
class Entry extends ALoader
{
    /**
     * Transform input values to something more reliable
     * @param string $source
     * @param array $array
     * @return Inputs\Entry[]
     */
    public function loadVars(string $source, &$array): array
    {
        $result = [];
        $entries = new Inputs\Entry();
        foreach ($array as $postedKey => $posted) {
            $entry = clone $entries;
            $entry->setEntry($source, $postedKey, $posted);
            $result[] = $entry;
        }
        return $result;
    }
}
