<?php

namespace SimplifiedTests;


use CommonTestClass;
use kalanis\kw_input\InputException;
use kalanis\kw_input\Simplified;


class ServerAdapterTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Simplified\ServerAdapter();
        $this->assertInstanceOf(\ArrayAccess::class, $data);
        $this->assertTrue(isset($data->PHP_SELF));
        $this->assertTrue(isset($data['PHP_SELF']));
        $data->PHP_SELF;
        $data['PHP_SELF'];
    }

    public function testDie1(): void
    {
        $data = new Simplified\ServerAdapter();
        $this->expectException(InputException::class);
        $data->foz = 'wuz';
    }

    public function testDie2(): void
    {
        $data = new Simplified\ServerAdapter();
        $this->expectException(InputException::class);
        unset($data->foz);
    }

    public function testIterator(): void
    {
        $data = new Simplified\ServerAdapter();
        $this->assertNotEmpty(iterator_to_array($data->getIterator()));
    }
}
