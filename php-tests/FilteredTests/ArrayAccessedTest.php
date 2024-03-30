<?php

namespace FilteredTests;


use ArrayObject;
use CommonTestClass;
use kalanis\kw_input\Filtered;
use kalanis\kw_input\Interfaces;


class ArrayAccessedTest extends CommonTestClass
{
    public function testProcessing(): void
    {
        $variables = new Filtered\ArrayAccessed(new ArrayObject([
            'foo' => 'val1',
            'bar' => ['bal1', 'bal2'],
            'baz' => true,
            'aff' => 42,
        ]), Interfaces\IEntry::SOURCE_CLI);

        /** @var Interfaces\IEntry[] $entries */
        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_GET]); // sources have no meaning here
        $input = new Filtered\FilterAdapter($variables, [Interfaces\IEntry::SOURCE_GET]);
        $this->assertNotEmpty(count($entries));

        $this->assertTrue(isset($input['foo']));
        $this->assertEquals('foo', $input['foo']->getKey());
        $this->assertEquals('val1', $input['foo']->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_CLI, $input['foo']->getSource());

        $this->assertTrue($input->offsetExists('bar'));
        $this->assertEquals('bar', $input->offsetGet('bar')->getKey());
        $this->assertEquals(['bal1', 'bal2'], $input->offsetGet('bar')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_CLI, $input->offsetGet('bar')->getSource());

        $this->assertTrue(isset($input->baz));
        $this->assertEquals('baz', $input->baz->getKey());
        $this->assertEquals(true, $input->baz->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_CLI, $input->baz->getSource());

        $this->assertTrue($input->offsetExists('aff'));
        $this->assertEquals('aff', $input->offsetGet('aff')->getKey());
        $this->assertEquals(42, $input->offsetGet('aff')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_CLI, $input->offsetGet('aff')->getSource());
    }
}
