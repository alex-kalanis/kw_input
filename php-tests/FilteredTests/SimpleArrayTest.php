<?php

namespace FilteredTests;


use CommonTestClass;
use kalanis\kw_input\Filtered;
use kalanis\kw_input\Interfaces;


class SimpleArrayTest extends CommonTestClass
{
    public function testProcess(): void
    {
        $variables = new Filtered\SimpleFromArrays([
            'foo' => 'val1',
            'bar' => ['bal1', 'bal2'],
            'baz' => true,
            'aff' => 42,
        ], Interfaces\IEntry::SOURCE_POST);

        /** @var Interfaces\IEntry[] $entries */
        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_GET]); // sources have no meaning here
        $input = new Filtered\FilterAdapter($variables, [Interfaces\IEntry::SOURCE_GET]);
        $this->assertNotEmpty(iterator_to_array($input->getIterator()));
        $this->assertNotEmpty(count($entries));

        $this->assertTrue(isset($input['foo']));
        $this->assertEquals('foo', $input['foo']->getKey());
        $this->assertEquals('val1', $input['foo']->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_POST, $input['foo']->getSource());

        $this->assertTrue($input->offsetExists('bar'));
        $this->assertEquals('bar', $input->offsetGet('bar')->getKey());
        $this->assertEquals(['bal1', 'bal2'], $input->offsetGet('bar')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_POST, $input->offsetGet('bar')->getSource());

        $this->assertTrue(isset($input->baz));
        $this->assertEquals('baz', $input->baz->getKey());
        $this->assertEquals(true, $input->baz->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_POST, $input->baz->getSource());

        $this->assertTrue($input->offsetExists('aff'));
        $this->assertEquals('aff', $input->offsetGet('aff')->getKey());
        $this->assertEquals(42, $input->offsetGet('aff')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_POST, $input->offsetGet('aff')->getSource());
    }
}
