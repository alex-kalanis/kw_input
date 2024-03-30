<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_input\Parsers;


class CliTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Parsers\Cli();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->cliDataset();
        $entries = $data->parseInput($dataset);

        $entry = reset($entries);
        $this->assertEquals('testing', key($entries));
        $this->assertEquals('foo', $entry);
        $entry = next($entries);
        $this->assertEquals('bar', key($entries));
        $this->assertEquals(['baz', 'eek'], $entry);
        $entry = next($entries);
        $this->assertEquals('mko', key($entries));
        $this->assertEquals('', $entry);
        $entry = next($entries);
        $this->assertEquals('der', key($entries));
        $this->assertEquals(true, $entry);
        $entry = next($entries);
        $this->assertEquals('file1', key($entries));
        $this->assertEquals('./data/tester.gif', $entry);
        $entry = next($entries);
        $this->assertEquals('file2', key($entries));
        $this->assertEquals('data/testing.1.txt', $entry);
        $entry = next($entries);
        $this->assertEquals('file3', key($entries));
        $this->assertEquals('./data/testing.2.txt', $entry);
        $entry = next($entries);
        $this->assertEquals('a', key($entries));
        $entry = next($entries);
        $this->assertEquals('b', key($entries));
        $entry = next($entries);
        $this->assertEquals('c', key($entries));
        $entry = next($entries);
        $this->assertEquals('known', $entry);
        $entry = next($entries);
        $this->assertEquals('what', $entry);
    }

    public function testStrange(): void
    {
        $data = new Parsers\Cli();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->strangeCliDataset();
        $entries = $data->parseInput($dataset);

        $entry = reset($entries);
        $this->assertEquals('testing', key($entries));
        $this->assertEquals('f<o>o', $entry);
        $entry = next($entries);
        $this->assertEquals('-bar', key($entries));
        $this->assertEquals('b**a**z', $entry);
        $entry = next($entries);
        $this->assertEquals('a', key($entries));
        $entry = next($entries);
        $this->assertEquals('c', key($entries));
    }
}
