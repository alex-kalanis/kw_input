<?php

namespace TraitsTests;


use kalanis\kw_input\Interfaces;
use kalanis\kw_input\Entries;
use kalanis\kw_input\Traits;


class KVTest extends ATraitsTest
{
    public function testFilterKeysValues(): void
    {
        $lib = new XTKV();
        $data = $lib->unpackValues($this->inputEntries());

        $item = reset($data);
        $this->assertNotFalse($item);
        $this->assertEquals('foo', key($data));
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('foo', $item->getKey());
        $this->assertEquals('mjgdf', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        $this->assertEquals('bar', key($data));
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_ENV, $item->getSource());
        $this->assertEquals('bar', $item->getKey());
        $this->assertEquals('shshh', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        $this->assertEquals('baz', key($data));
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('baz', $item->getKey());
        $this->assertEquals('gnbgy', $item->getValue());

        $item = next($data);
        $this->assertFalse($item);
    }
}


class XTKV
{
    use Traits\TKV;

    public function unpackValues(array $entries): array
    {
        return $this->keysValues($entries);
    }
}
