<?php

namespace FilteredTests;


use CommonTestClass;
use kalanis\kw_input\Filtered;
use kalanis\kw_input\Interfaces;


class EntryArraysTest extends CommonTestClass
{
    public function testProcess(): void
    {
        $variables = new Filtered\EntryArrays([
            ExEntry::init(Interfaces\IEntry::SOURCE_GET, 'foo', 'val1'),
            ExEntry::init(Interfaces\IEntry::SOURCE_GET, 'bar', ['bal1', 'bal2']),
            ExEntry::init(Interfaces\IEntry::SOURCE_GET, 'baz', true),
            ExEntry::init(Interfaces\IEntry::SOURCE_GET, 'aff', 42),
            ExEntry::init(Interfaces\IEntry::SOURCE_EXTERNAL, 'uhb', 'feaht'),
        ]);

        /** @var Interfaces\IEntry[] $entries */
        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_GET]);
        $input = new Filtered\FilterAdapter($variables, [Interfaces\IEntry::SOURCE_GET]);
        $this->assertNotEmpty(iterator_to_array($input->getIterator()));
        $this->assertNotEmpty(count($entries));

        $this->assertTrue(isset($entries['foo']));
        $this->assertEquals('foo', $entries['foo']->getKey());
        $this->assertEquals('val1', $entries['foo']->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $entries['foo']->getSource());

        $this->assertTrue($input->offsetExists('bar'));
        $this->assertEquals('bar', $input->offsetGet('bar')->getKey());
        $this->assertEquals(['bal1', 'bal2'], $input->offsetGet('bar')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->offsetGet('bar')->getSource());

        $this->assertTrue(isset($input->baz));
        $this->assertEquals('baz', $input->baz->getKey());
        $this->assertEquals(true, $input->baz->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->baz->getSource());

        $this->assertTrue($input->offsetExists('aff'));
        $this->assertEquals('aff', $input->offsetGet('aff')->getKey());
        $this->assertEquals(42, $input->offsetGet('aff')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->offsetGet('aff')->getSource());

        $this->assertFalse($input->offsetExists('uhb'));
        $input->offsetSet('uhb', 'feaht');
        $this->assertEquals('feaht', $input->offsetGet('uhb')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $input->offsetGet('uhb')->getSource());
    }
}
