<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\kw_input\Traits;


class NullBytesTest extends CommonTestClass
{
    public function testNullBytes(): void
    {
        $lib = new XNull();
        $this->assertEquals('dfghdfhdhbdrhzh', $lib->removeNullBytes('dfghdf' . chr(0) . chr(0) . 'hdhbd' . chr(0) . 'rhzh'));
    }
}


class XNull
{
    use Traits\TNullBytes;
}

