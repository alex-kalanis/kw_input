<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Parsers;


class FactoryTest extends CommonTestClass
{
    public function testFactory(): void
    {
        $factory = new Parsers\Factory();
        $loader1 = $factory->getLoader(IEntry::SOURCE_GET);
        $loader2 = $factory->getLoader(IEntry::SOURCE_GET); // intentionally same
        $loader3 = $factory->getLoader(IEntry::SOURCE_CLI);
        $loader4 = $factory->getLoader(IEntry::SOURCE_FILES);
        $loader5 = $factory->getLoader(IEntry::SOURCE_JSON);

        $this->assertInstanceOf(Parsers\Basic::class, $loader1);
        $this->assertInstanceOf(Parsers\Basic::class, $loader2);
        $this->assertInstanceOf(Parsers\Files::class, $loader4);
        $this->assertInstanceOf(Parsers\Cli::class, $loader3);
        $this->assertInstanceOf(Parsers\Json::class, $loader5);
        $this->assertEquals($loader1, $loader2);
        $this->assertNotEquals($loader3, $loader2);
        $this->assertNotEquals($loader3, $loader4);
        $this->assertNotEquals($loader2, $loader4);
        $this->assertNotEquals($loader2, $loader5);
    }
}
