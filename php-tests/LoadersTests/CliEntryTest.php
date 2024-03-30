<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;
use kalanis\kw_input\Parsers;


class CliEntryTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Loaders\CliEntry();
        $this->assertInstanceOf(Loaders\ALoader::class, $data);

        $cli = new Parsers\Cli();
        $entries = $data->loadVars(IEntry::SOURCE_CLI, $cli->parseInput($this->cliDataset()));

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('testing', $entry->getKey());
        $this->assertEquals('foo', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['baz', 'eek'], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('mko', $entry->getKey());
        $this->assertEquals('', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('der', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('file1', $entry->getKey());
        $this->assertEquals('./data/tester.gif', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('file2', $entry->getKey());
        $this->assertEquals('data/testing.1.txt', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('file3', $entry->getKey());
        $this->assertEquals('./data/testing.2.txt', $entry->getValue());
    }
}
