<?php

namespace kalanis\kw_input\Interfaces;


use Traversable;


/**
 * Interface IInputs
 * @package kalanis\kw_input
 * Basic interface which tells us what actions are by default available by inputs
 */
interface IInputs
{
    /**
     * Setting the variable sources - from cli (argv), _GET, _POST, _SERVER, ...
     * @param ISource|array|null $source
     * @return $this
     */
    public function setSource($source = null): self;

    /**
     * Load entries from source into the local entries which will be accessible
     * These two calls came usually in pair
     *
     * $input->setSource($argv)->loadInputs();
     */
    public function loadEntries(): void;

    /**
     * Get iterator of local entries, filter them on way
     * @param string|null $entryKey
     * @param string[] $entrySources array of constants from Entries\IEntry::SOURCE_*
     * @return Traversable iterator for foreach
     * @see IEntry::SOURCE_CLI
     * @see IEntry::SOURCE_GET
     * @see IEntry::SOURCE_POST
     * @see IEntry::SOURCE_FILES
     * @see IEntry::SOURCE_SESSION
     * @see IEntry::SOURCE_SERVER
     * @see IEntry::SOURCE_ENV
     */
    public function getIn(string $entryKey = null, array $entrySources = []): Traversable;

    /**
     * Reformat iterator from getIn() into array with key as array key and value with the whole entry
     * @param Traversable $entries
     * @return IEntry[]
     * Also usually came in pair with previous call - but with a different syntax
     * Beware - due any dict limitations there is a limitation that only the last entry prevails
     *
     * $entries = $input->intoKeyObjectArray($input->getIn('example', [Entries\IEntry::SOURCE_GET]));
     */
    public function intoKeyObjectArray(Traversable $entries): array;
}