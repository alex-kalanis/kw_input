<?php

namespace kalanis\kw_input\Inputs;


/**
 * Interface IEntry
 * @package kalanis\kw_input\Inputs
 * Entry interface - this will be shared across the projects
 */
interface IEntry
{
    const SOURCE_CLI = 'cli';
    const SOURCE_GET = 'get';
    const SOURCE_POST = 'post';
    const SOURCE_FILES = 'files';
    const SOURCE_SESSION = 'session';
    const SOURCE_SERVER = 'server';
    const SOURCE_ENV = 'environment';

    /**
     * Return source of entry
     * @return string
     */
    public function getSource(): string;

    /**
     * Return key of entry
     * @return string
     */
    public function getKey(): string;

    /**
     * Return value of entry
     * It could be anything - string, boolean, array - depends on source
     * @return string
     */
    public function getValue();
}
