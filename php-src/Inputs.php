<?php

namespace kalanis\kw_input;


use Traversable;


/**
 * Class Inputs
 * @package kalanis\kw_input
 * Base class for passing info from inputs into objects
 */
class Inputs implements IInputs
{
    /** @var Entries\IEntry[] */
    protected $entries = [];
    /** @var Sources\ISource */
    protected $source = null;
    /** @var Parsers\Factory */
    protected $parserFactory = null;
    /** @var Loaders\Factory */
    protected $loaderFactory = null;

    public function __construct()
    {
        $this->parserFactory = new Parsers\Factory();
        $this->loaderFactory = new Loaders\Factory();
        $this->source = new Sources\Basic();
    }

    public function setSource($source = null): IInputs
    {
        if (!empty($source) && ($source instanceof Sources\ISource)) {
            $this->source = $source;
        } elseif (($this->source instanceof Sources\Basic) && is_array($source)) {
            $this->source->setCli($source);
        }
        return $this;
    }

    public function loadEntries(): void
    {
        $this->entries = array_merge(
            $this->loadInput(Entries\IEntry::SOURCE_GET, $this->source->get()),
            $this->loadInput(Entries\IEntry::SOURCE_POST, $this->source->post()),
            $this->loadInput(Entries\IEntry::SOURCE_CLI, $this->source->cli()),
            $this->loadInput(Entries\IEntry::SOURCE_SESSION, $this->source->session()),
            $this->loadInput(Entries\IEntry::SOURCE_FILES, $this->source->files()),
            $this->loadInput(Entries\IEntry::SOURCE_ENV, $this->source->env()),
            $this->loadInput(Entries\IEntry::SOURCE_SERVER, $this->source->server()),
            $this->loadInput(Entries\IEntry::SOURCE_EXTERNAL, $this->source->external())
        );
    }

    protected function loadInput(string $source, ?array &$inputArray = null): array
    {
        if (empty($inputArray)) {
            return [];
        }
        $parser = $this->parserFactory->getLoader($source);
        $loader = $this->loaderFactory->getLoader($source);
        return $loader->loadVars($source, $parser->parseInput($inputArray));
    }

    public function getBasic(): Traversable
    {
        return $this->getIn(null, [
            Entries\IEntry::SOURCE_CLI,
            Entries\IEntry::SOURCE_GET,
            Entries\IEntry::SOURCE_POST,
        ]);
    }

    public function getSystem(): Traversable
    {
        return $this->getIn(null, [
            Entries\IEntry::SOURCE_SERVER,
            Entries\IEntry::SOURCE_ENV,
        ]);
    }

    public function getCli(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_CLI]);
    }

    public function getGet(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_GET]);
    }

    public function getPost(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_POST]);
    }

    public function getSession(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_SESSION]);
    }

    public function getFiles(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_FILES]);
    }

    public function getServer(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_SERVER]);
    }

    public function getEnv(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_ENV]);
    }

    public function getExternal(): Traversable
    {
        return $this->getIn(null, [Entries\IEntry::SOURCE_EXTERNAL]);
    }

    public function getIn(string $entryKey = null, array $entrySources = []): Traversable
    {
        foreach ($this->entries as $entry) {
            $allowedByKey = empty($entryKey) || ($entry->getKey() == $entryKey);
            $allowedBySource = empty($entrySources) || in_array($entry->getSource(), $entrySources);
            if ($allowedByKey && $allowedBySource) {
                yield $entry;
            }
        }
    }

    /**
     * @param Traversable $entries
     * @return Entries\IEntry[]
     */
    public function intoKeyObjectArray(Traversable $entries): array
    {
        $result = [];
        foreach ($entries as $entry) {
            /** @var Entries\IEntry $entry */
            $result[$entry->getKey()] = $entry;
        }
        return $result;
    }
}