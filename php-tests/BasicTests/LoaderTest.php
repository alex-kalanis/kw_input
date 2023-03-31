<?php

use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;
use kalanis\kw_input\Parsers;


class LoaderTest extends CommonTestClass
{
    protected $tempFile = '';

    protected function tearDown(): void
    {
        parent::tearDown();
        if (is_file($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testFactory(): void
    {
        $factory = new Loaders\Factory();
        $loader1 = $factory->getLoader(IEntry::SOURCE_GET);
        $loader2 = $factory->getLoader(IEntry::SOURCE_GET); // intentionally same
        $loader3 = $factory->getLoader(IEntry::SOURCE_FILES);
        $loader4 = $factory->getLoader(IEntry::SOURCE_JSON);

        $this->assertInstanceOf(Loaders\Entry::class, $loader1);
        $this->assertInstanceOf(Loaders\Entry::class, $loader2);
        $this->assertInstanceOf(Loaders\File::class, $loader3);
        $this->assertInstanceOf(Loaders\Json::class, $loader4);
        $this->assertEquals($loader1, $loader2);
        $this->assertNotEquals($loader3, $loader2);
        $this->assertNotEquals($loader3, $loader4);
    }

    public function testEntry(): void
    {
        $data = new Loaders\Entry();
        $this->assertInstanceOf(Loaders\ALoader::class, $data);

        $entries = $data->loadVars(IEntry::SOURCE_GET, $this->entryDataset());

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('val1', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['bal1', 'bal2'], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_GET, $entry->getSource());
        $this->assertEquals('aff', $entry->getKey());
        $this->assertEquals(42, $entry->getValue());
    }

    public function testFile(): void
    {
        $data = new Loaders\File();
        $this->assertInstanceOf(Loaders\ALoader::class, $data);

        $entries = $data->loadVars(IEntry::SOURCE_FILES, $this->fileDataset());

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('files', $entry->getKey());
        $this->assertEquals('facepalm.jpg', $entry->getValue());
        $this->assertEquals('image/jpeg', $entry->getMimeType());
        $this->assertEquals('/tmp/php3zU3t5', $entry->getTempName());
        $this->assertEquals(UPLOAD_ERR_OK, $entry->getError());
        $this->assertEquals(591387, $entry->getSize());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('download[file1]', $entry->getKey());
        $this->assertEquals('MyFile.txt', $entry->getValue());
        $this->assertEquals('text/plain', $entry->getMimeType());
        $this->assertEquals('/tmp/php/phpgj46fg', $entry->getTempName());
        $this->assertEquals(UPLOAD_ERR_CANT_WRITE, $entry->getError());
        $this->assertEquals(816, $entry->getSize());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('download[file2]', $entry->getKey());
        $this->assertEquals('MyFile.jpg', $entry->getValue());
        $this->assertEquals('image/jpeg', $entry->getMimeType());
        $this->assertEquals('/tmp/php/php7s4ag4', $entry->getTempName());
        $this->assertEquals(UPLOAD_ERR_PARTIAL, $entry->getError());
        $this->assertEquals(3075, $entry->getSize());
    }

    public function testCliFile(): void
    {
        $data = new Loaders\CliEntry();
        $this->assertInstanceOf(Loaders\ALoader::class, $data);

        $cli = new Parsers\Cli();
        $entries = $data->loadVars(IEntry::SOURCE_CLI, $cli->parseInput($this->cliDataset()));

        $entry = reset($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('testing', $entry->getKey());
        $this->assertEquals('foo', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['baz', 'eek'], $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('mko', $entry->getKey());
        $this->assertEquals('', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('der', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('file1', $entry->getKey());
        $this->assertEquals('./data/tester.gif', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_FILES, $entry->getSource());
        $this->assertEquals('file2', $entry->getKey());
        $this->assertEquals('data/testing.1.txt', $entry->getValue());

        $entry = next($entries);
        $this->assertEquals(IEntry::SOURCE_CLI, $entry->getSource());
        $this->assertEquals('file3', $entry->getKey());
        $this->assertEquals('./data/testing.2.txt', $entry->getValue());
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
