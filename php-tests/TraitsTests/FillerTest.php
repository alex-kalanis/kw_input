<?php

namespace TraitsTests;


use kalanis\kw_input\Interfaces;
use kalanis\kw_input\Entries;
use kalanis\kw_input\Traits;
use stdClass;


class FillerTest extends ATraitsTest
{
    public function testFilledEntries(): void
    {
        $lib = new XFill();
        $this->checkData($lib->fromEntries(Interfaces\IEntry::SOURCE_EXTERNAL, $this->inputData()));
    }

    public function testFilledIterator(): void
    {
        $lib = new XFill();
        $this->checkData($lib->fromIterator(Interfaces\IEntry::SOURCE_EXTERNAL, new \ArrayIterator($this->inputData())));
    }

    protected function inputData(): array
    {
        return [
            'foz' => 'wuz',
            'ugg' . chr(0) => 'huu' . chr(0),
            'asd' => $this->getSimpleObject(),
            'ghd' => (new Entries\Entry())->setEntry(Entries\Entry::SOURCE_RAW, 'ghd', 'ankyxgf'),
        ];
    }

    protected function getSimpleObject(): object
    {
        $obj = new stdClass();
        $obj->foo = 'bar';
        return $obj;
    }

    protected function checkData(array $data): void
    {
        $item = reset($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('foz', $item->getKey());
        $this->assertEquals('wuz', $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('ugg', $item->getKey());
        $this->assertEquals('huu' . chr(0), $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('asd', $item->getKey());
        $this->assertInstanceOf(stdClass::class, $item->getValue());

        $item = next($data);
        $this->assertNotFalse($item);
        /** @var Entries\Entry $item */
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $item->getSource());
        $this->assertEquals('ghd', $item->getKey());
        $this->assertEquals('ankyxgf', $item->getValue());

        $item = next($data);
        $this->assertFalse($item);
    }
}


class XFill
{
    use Traits\TFill;

    public function fromEntries(string $source, array $entries): array
    {
        return $this->fillFromEntries($source, $entries);
    }

    public function fromIterator(string $source, iterable $iterator): array
    {
        return $this->fillFromIterator($source, $iterator);
    }
}
