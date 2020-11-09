<?php

namespace kalanis\kw_input;


use Traversable;


/**
 * Class Inputs
 * @package kalanis\kw_input
 * Base class for passing info from input
 */
class Inputs
{
    /** @var Inputs\IEntry[] */
    protected $entries = [];
    /** @var Sources\ISource */
    protected $source = null;
    /** @var Parsers\Factory */
    protected $parserFactory = null;
    /** @var Loaders\Factory */
    protected $loaderFactory = null;

    public function __construct(?Sources\ISource $source = null)
    {
        $this->parserFactory = new Parsers\Factory();
        $this->loaderFactory = new Loaders\Factory();
        $this->source = (empty($source)) ? new Sources\Basic() : $source ;
    }

    public function loadInputs(array $cliArgs = [])
    {
        $this->entries = array_merge(
            $this->loadInput(Inputs\IEntry::SOURCE_GET, $this->source->get()),
            $this->loadInput(Inputs\IEntry::SOURCE_POST, $this->source->post()),
            $this->loadInput(Inputs\IEntry::SOURCE_CLI, $cliArgs),
            $this->loadInput(Inputs\IEntry::SOURCE_SESSION, $this->source->session()),
            $this->loadInput(Inputs\IEntry::SOURCE_FILES, $this->source->files()),
            $this->loadInput(Inputs\IEntry::SOURCE_ENV, $this->source->env()),
            $this->loadInput(Inputs\IEntry::SOURCE_SERVER, $this->source->server())
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
        return $this->getIn([
            Inputs\IEntry::SOURCE_CLI,
            Inputs\IEntry::SOURCE_GET,
            Inputs\IEntry::SOURCE_POST,
        ]);
    }

    public function getSystem(): Traversable
    {
        return $this->getIn([
            Inputs\IEntry::SOURCE_SERVER,
            Inputs\IEntry::SOURCE_ENV,
        ]);
    }

    public function getCli(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_CLI]);
    }

    public function getGet(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_GET]);
    }

    public function getPost(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_POST]);
    }

    public function getSession(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_SESSION]);
    }

    public function getFiles(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_FILES]);
    }

    public function getServer(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_SERVER]);
    }

    public function getEnv(): Traversable
    {
        return $this->getIn([Inputs\IEntry::SOURCE_ENV]);
    }

    public function getIn(array $sources): Traversable
    {
        foreach ($this->entries as $entry) {
            if (in_array($entry->getSource(), $sources)) {
                yield $entry;
            }
        }
    }

    /**
     * @param Traversable $entries
     * @return Inputs\IEntry[]
     */
    public function intoKeyObjectArray(Traversable $entries): array
    {
        $result = [];
        foreach ($entries as $entry) {
            /** @var Inputs\IEntry $entry */
            $result[$entry->getKey()] = $entry;
        }
        return $result;
    }
}