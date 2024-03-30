<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;


class FactoryTest extends CommonTestClass
{
    public function testPass(): void
    {
        $factory = new Loaders\Factory();
        $loader1 = $factory->getLoader(IEntry::SOURCE_GET);
        $loader2 = $factory->getLoader(IEntry::SOURCE_GET); // intentionally same
        $loader3 = $factory->getLoader(IEntry::SOURCE_FILES);
        $loader4 = $factory->getLoader(IEntry::SOURCE_JSON);
        $factory->getLoader(IEntry::SOURCE_CLI);

        $this->assertInstanceOf(Loaders\Entry::class, $loader1);
        $this->assertInstanceOf(Loaders\Entry::class, $loader2);
        $this->assertInstanceOf(Loaders\File::class, $loader3);
        $this->assertInstanceOf(Loaders\Json::class, $loader4);
        $this->assertEquals($loader1, $loader2);
        $this->assertNotEquals($loader3, $loader2);
        $this->assertNotEquals($loader3, $loader4);
    }
}
