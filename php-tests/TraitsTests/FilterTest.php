<?php

namespace TraitsTests;


use kalanis\kw_input\Interfaces;
use kalanis\kw_input\Entries;
use kalanis\kw_input\Traits;


class FilterTest extends ATraitsTest
{
    public function testFilterKeyNoParam(): void
    {
        $lib = new XFilter();
        $this->checkAllEntries($lib->filterKeys(null, $this->inputEntries()));
    }

    public function testFilterKeyUnknownParam(): void
    {
        $lib = new XFilter();
        $this->assertEmpty($lib->filterKeys('not-exists', $this->inputEntries()));
    }

    public function testFilterKeySoloParam(): void
    {
        $lib = new XFilter();
        $data = $lib->filterKeys('bar', $this->inputEntries());

        $item = reset($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('hjyxn', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('shshh', $item->getValue());

        $item = next($data);
        $this->assertFalse($item);
    }

    public function testFilterSourceNoParam(): void
    {
        $lib = new XFilter();
        $this->checkAllEntries($lib->filterSource([], $this->inputEntries()));
    }

    public function testFilterSourceUnknownParam(): void
    {
        $lib = new XFilter();
        $this->assertEmpty($lib->filterSource([Entries\Entry::SOURCE_JSON], $this->inputEntries()));
    }

    public function testFilterSourceSoloParam(): void
    {
        $lib = new XFilter();
        $data = $lib->filterSource([Entries\Entry::SOURCE_ENV], $this->inputEntries());

        $item = reset($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('foo', $item->getKey());
        $this->assertEquals('mjgdf', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('shshh', $item->getValue());

        $item = next($data);
        $this->assertFalse($item);
    }

    protected function checkAllEntries(array $data): void
    {
        $item = reset($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('foo', $item->getKey());
        $this->assertEquals('fhdfh', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('hjyxn', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('baz', $item->getKey());
        $this->assertEquals('gnbgy', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('foo', $item->getKey());
        $this->assertEquals('mjgdf', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('shshh', $item->getValue());

        $item = next($data);
        $this->assertFalse($item);
    }
}


class XFilter
{
    use Traits\TFilter;

    public function filterKeys(?string $entryKey, array $availableEntries): array
    {
        return $this->whichKeys($entryKey, $availableEntries);
    }

    public function filterSource(array $entrySources, array $entriesWithKeys): array
    {
        return $this->whichSource($entrySources, $entriesWithKeys);
    }
}
