<?php

namespace FilteredTests;


use CommonTestClass;
use kalanis\kw_input\Filtered;
use kalanis\kw_input\InputException;
use kalanis\kw_input\Interfaces;


class JsonTest extends CommonTestClass
{
    /**
     * @throws InputException
     */
    public function testJson(): void
    {
        $variables = new Filtered\Json($this->jsonDataset());

        /** @var Interfaces\IEntry[] $entries */
        $entries = $variables->getInArray(); // sources have no meaning here
        $this->assertNotEmpty(count($entries));

        $entries = $variables->getInArray(); // sources have no meaning here
        $entry = reset($entries);
        $this->assertEquals(Interfaces\IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('bar', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(Interfaces\IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(['rfv' => 123, 'edc'=> 456], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(Interfaces\IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('sbr', $entry->getKey());
        $this->assertEquals(['cde', 'dgs'], $entry->getValue());
    }

    /**
     * @throws InputException
     */
    public function testJsonCrash(): void
    {
        $this->expectException(InputException::class);
        new Filtered\Json('not a Json string\0{');
    }
}
