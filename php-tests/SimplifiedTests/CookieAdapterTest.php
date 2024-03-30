<?php

namespace SimplifiedTests;


use CommonTestClass;
use kalanis\kw_input\InputException;
use kalanis\kw_input\Simplified;


class CookieAdapterTest extends CommonTestClass
{
    public function testPass(): void
    {
        Simplified\CookieAdapter::init('', '', null, false, false, false);
        $data = new Simplified\CookieAdapter();
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
    }

    public function testDie1(): void
    {
        Simplified\CookieAdapter::init('', '', null, false, false, false, true);
        $data = new Simplified\CookieAdapter();
        $this->expectException(InputException::class);
        $data->foz = 'wuz';
    }

    public function testDie2(): void
    {
        Simplified\CookieAdapter::init('', '', null, false, false, false, true);
        $data = new Simplified\CookieAdapter();
        $this->expectException(InputException::class);
        unset($data->foz);
    }

    public function testIterator(): void
    {
        Simplified\CookieAdapter::init('', '', null, false, false, false);
        $data = new Simplified\CookieAdapter();

        $this->assertEmpty(iterator_to_array($data->getIterator()));
        $data->foz = 'wuz';
        $this->assertNotEmpty(iterator_to_array($data->getIterator()));
        unset($data->foz);
        $this->assertEmpty(iterator_to_array($data->getIterator()));
    }
}
