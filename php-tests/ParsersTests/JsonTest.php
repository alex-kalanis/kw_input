<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Parsers;


class JsonTest extends CommonTestClass
{
    public function testNoSource(): void
    {
        $lib = new Parsers\Json();
        $this->assertInstanceOf(Parsers\AParser::class, $lib);
        $this->assertEmpty($lib->parseInput([]));
    }

    public function testNoFile(): void
    {
        $lib = new Parsers\Json();
        $this->assertInstanceOf(Parsers\AParser::class, $lib);
        $this->assertEmpty($lib->parseInput([__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'not_exists']));
    }

    public function testMalformedContent(): void
    {
        $lib = new Parsers\Json();
        $this->assertInstanceOf(Parsers\AParser::class, $lib);
        $this->assertEmpty($lib->parseInput([__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'malformed_json.txt']));
    }

    public function testPass(): void
    {
        $lib = new Parsers\Json();
        $this->assertInstanceOf(Parsers\AParser::class, $lib);
        $data = $lib->parseInput([__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'pass_json.txt']);

        $this->assertNotEmpty($data);
    }
}
