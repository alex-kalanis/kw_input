<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_input\Parsers;


class BasicTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Parsers\Basic();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->entryDataset();
        $entries = $data->parseInput($dataset);

        $entry = reset($entries);
        $this->assertEquals('foo', key($entries));
        $this->assertEquals('val1', $entry);

        $entry = next($entries);
        $this->assertEquals('bar', key($entries));
        $this->assertEquals(['bal1', 'bal2'], $entry);

        $entry = next($entries);
        $this->assertEquals('baz', key($entries));
        $this->assertEquals(true, $entry);

        $entry = next($entries);
        $this->assertEquals('aff', key($entries));
        $this->assertEquals(42, $entry);
    }

    public function testStrange(): void
    {
        $data = new Parsers\Basic();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->strangeEntryDataset();
        $entries = $data->parseInput($dataset);

        $entry = reset($entries);
        $this->assertEquals('foo  ', key($entries));
        $this->assertEquals('val1', $entry);

        $entry = next($entries);
        $this->assertEquals('bar', key($entries));
        $this->assertEquals(["<script>alert('XSS!!!')</script>", 'bal2'], $entry);

        $entry = next($entries);
        $this->assertEquals('b<a>z', key($entries));
        $this->assertEquals(false, $entry);

        $entry = next($entries);
        $this->assertEquals('a**ff', key($entries));
        $this->assertEquals('<?php echo "ded!";', $entry);
    }
}
