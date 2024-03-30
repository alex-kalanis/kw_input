<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;
use kalanis\kw_input\Parsers;


class JsonTest extends CommonTestClass
{
    protected $tempFile = '';

    protected function tearDown(): void
    {
        parent::tearDown();
        if (is_file($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testJson(): void
    {
        $parser = new Parsers\Json();
        $loader = new Loaders\Json();

        $this->assertInstanceOf(Loaders\ALoader::class, $loader);

        $this->setTempData($this->jsonDataset());

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([$this->tempFile]));

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('bar', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(['rfv' => 123, 'edc'=> 456], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('sbr', $entry->getKey());
        $this->assertEquals(['cde', 'dgs'], $entry->getValue());
    }

    public function testJsonString(): void
    {
        $parser = new Parsers\Json();
        $loader = new Loaders\Json();

        $this->assertInstanceOf(Loaders\ALoader::class, $loader);

        $this->setTempData($this->jsonStringDataset());

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([$this->tempFile]));

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals(0, $entry->getKey());
        $this->assertEquals('Just content', $entry->getValue());
    }

    public function testJsonFile(): void
    {
        $loader = new Loaders\Json();
        $parser = new Parsers\Json();

        $this->assertInstanceOf(Loaders\ALoader::class, $loader);

        $this->setTempData($this->jsonFileDataset());

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([$this->tempFile]));

        /** @var \kalanis\kw_input\Entries\FileEntry $entry */
        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('foo.json', $entry->getValue());

        // now file content
        $this->assertNotEmpty($entry->getTempName());
        $this->assertEquals('application/octet-stream', $entry->getMimeType());
        $this->assertEquals(21, $entry->getSize());
        $this->assertEquals('This won' . chr(0) . 't be changed', file_get_contents($entry->getTempName()));
        @unlink($entry->getTempName());

        // second record is not a file
        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_JSON, $entry->getSource());
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['ijn' => ['FILE' => 'This will be changed']], $entry->getValue());
    }

    public function testJsonNothing(): void
    {
        $loader = new Loaders\Json();
        $parser = new Parsers\Json();

        $this->setTempData('{}');

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([$this->tempFile]));

        $this->assertEmpty($entries);
    }

    public function testJsonBadToDecode(): void
    {
        $loader = new Loaders\Json();
        $parser = new Parsers\Json();

        $this->setTempData('This is not a valid JSON string {\0');

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([$this->tempFile]));

        $this->assertEmpty($entries);
    }

    public function testJsonNoToDecode(): void
    {
        $loader = new Loaders\Json();
        $parser = new Parsers\Json();

        $this->setTempData('This is not a valid JSON string {\0');

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput([]));

        $this->assertEmpty($entries);
    }

    public function testJsonNoFile(): void
    {
        $loader = new Loaders\Json();
        $parser = new Parsers\Json();

        $entries = $loader->loadVars(IEntry::SOURCE_JSON, $parser->parseInput(['not_exists']));

        $this->assertEmpty($entries);
    }

    protected function setTempData(string $dataset): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'js_test_');
        file_put_contents($this->tempFile, $dataset);
    }
}
