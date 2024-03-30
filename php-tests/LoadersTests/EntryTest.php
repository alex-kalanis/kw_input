<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;


class EntryTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Loaders\Entry();
        $this->assertInstanceOf(Loaders\ALoader::class, $data);

        $entries = $data->loadVars(IEntry::SOURCE_GET, $this->entryDataset());

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('val1', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['bal1', 'bal2'], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('aff', $entry->getKey());
        $this->assertEquals(42, $entry->getValue());
    }
}
