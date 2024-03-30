<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\kw_input\Entries\Entry;
use kalanis\kw_input\Traits;


class InputEntriesTest extends CommonTestClass
{
    public function testProcess(): void
    {
        $lib = new XInputEntries();
        $this->assertFalse($lib->offsetExists('foo'));
        $lib->offsetSet('foo', 'baz');
        $this->assertTrue($lib->offsetExists('foo'));
        $this->assertEquals('baz', $lib->offsetGet('foo')->getValue());
        $lib->offsetSet('foo', 'oag');
        $this->assertTrue($lib->offsetExists('foo'));
        $this->assertEquals('oag', $lib->offsetGet('foo')->getValue());
        $lib->offsetUnset('foo');
        $this->assertFalse($lib->offsetExists('foo'));
    }

    public function testSetters(): void
    {
        $lib = new XInputEntries();
        $lib->offsetSet('foo', (new Entry())->setEntry('one', 'there', new \stdClass()));
        $lib->offsetSet('foo', (new Entry())->setEntry('two', 'dhh', null));
        $this->assertTrue($lib->offsetExists('foo'));
        $this->assertNull($lib->offsetGet('foo')->getValue());
    }

    public function testIterator(): void
    {
        $lib = new XInputEntries();
        $this->assertEmpty(iterator_to_array($lib->getIterator()));
    }
}


class XInputEntries
{
    use Traits\TInputEntries;

    protected function defaultSource(): string
    {
        return Entry::SOURCE_EXTERNAL;
    }
}
