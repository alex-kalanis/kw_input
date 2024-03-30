<?php

namespace SimplifiedTests;


use CommonTestClass;
use kalanis\kw_input\Simplified;


class SessionAdapterTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Simplified\SessionAdapter();
        $this->assertInstanceOf(\ArrayAccess::class, $data);

        $data->foz = 'wuz';
        $this->assertTrue(isset($data->foz));
        $this->assertEquals('wuz', $data->foz);
        unset($data->foz);

        $data['ugg'] = 'huu';
        $this->assertTrue(isset($data['ugg']));
        $this->assertEquals('huu', $data['ugg']);
        unset($data['ugg']);

        $nullKey = 'bnm' . chr(0) . 'lkj';
        $data[$nullKey] = 'thd';
        $this->assertTrue(isset($data[$nullKey]));
        $this->assertTrue(isset($data['bnmlkj']));
        $this->assertEquals('thd', $data[$nullKey]);
        $this->assertEquals('thd', $data['bnmlkj']);
        unset($data[$nullKey]);

        $data->gsr = 'vfr';
        $this->assertNotEmpty(iterator_to_array($data->getIterator()));
        unset($data->gsr);
        $this->assertEmpty(iterator_to_array($data->getIterator()));
    }

    public function testIterator(): void
    {
        $data = new Simplified\SessionAdapter();

        $this->assertEmpty(iterator_to_array($data->getIterator()));
        $data->foz = 'wuz';
        $this->assertNotEmpty(iterator_to_array($data->getIterator()));
        unset($data->foz);
        $this->assertEmpty(iterator_to_array($data->getIterator()));
    }
}
