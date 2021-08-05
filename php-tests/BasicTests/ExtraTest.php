<?php

use kalanis\kw_input\Extras;


class ExtraTest extends CommonTestClass
{
    public function testEntry()
    {
        $data = new Extras\SessionAdapter();
        $this->assertInstanceOf('\ArrayAccess', $data);

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
}
