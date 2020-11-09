<?php

namespace kalanis\kw_input\Parsers;


/**
 * Class Cli
 * @package kalanis\kw_input\Parsers
 * Parse input from command line
 */
class Cli extends AParser
{
    const DELIMITER_LONG_ENTRY = '--';
    const DELIMITER_SHORT_ENTRY = '-';
    const DELIMITER_PARAM_VALUE = '=';
    const UNSORTED_PARAM = 'param_';

    protected static $availableLetters = ['a','b','c','d','e','f','g','h','i','j','k','l','m',
                                          'n','o','p','q','r','s','t','u','v','w','x','y','z'];

    /**
     * @param array $input is $argv in boot time
     * @return array
     */
    public function &parseInput(&$input): array
    {
        $clearArray = [];
        $unsorted = 0;
        foreach ($input as &$posted) {
            if (0 === strpos($posted, static::DELIMITER_LONG_ENTRY)) {
                // large params
                if (false !== strpos($posted, static::DELIMITER_PARAM_VALUE)) {
                    $entry = substr($posted, strlen(static::DELIMITER_LONG_ENTRY));
                    list($key, $value) = explode(static::DELIMITER_PARAM_VALUE, $entry, 2);
                    $clearArray[$this->removeNullBytes($key)] = $this->removeNullBytes($value);
                } else {
                    $clearArray[$this->removeNullBytes($posted)] = true;
                }
            } elseif (0 === strpos($posted, static::DELIMITER_SHORT_ENTRY)) {
                // just by letters
                $entry = $this->removeNullBytes(substr($posted, strlen(static::DELIMITER_SHORT_ENTRY)));
                for ($i=0; $i<strlen($entry); $i++) {
                    if (in_array(strtolower($entry[$i]), static::$availableLetters)) {
                        $clearArray[$entry[$i]] = true;
                    }
                }
            } else {
                // rest of the world
                $key = static::UNSORTED_PARAM . $unsorted;
                $clearArray[$key] = $this->removeNullBytes($posted);
                $unsorted++;
            }
        }
        return $clearArray;
    }
}
