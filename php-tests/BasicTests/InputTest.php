<?php

use kalanis\kw_input\Entries;
use kalanis\kw_input\Inputs;
use kalanis\kw_input\Sources;


class InputTest extends CommonTestClass
{
    public function testEntry()
    {
        $input = new Inputs();
        $input->setSource($this->cliDataset()); // direct cli

        $source = new MockSource();
        $source->setRemotes($this->entryDataset(), null, $this->cliDataset());
        $input->setSource($source)->loadEntries();

        $this->assertNotEmpty(iterator_to_array($input->getCli()));
        $this->assertNotEmpty(iterator_to_array($input->getGet()));
        $this->assertEmpty(iterator_to_array($input->getPost()));
        $this->assertEmpty(iterator_to_array($input->getSession()));
        $this->assertEmpty(iterator_to_array($input->getFiles()));
        $this->assertEmpty(iterator_to_array($input->getServer()));
        $this->assertEmpty(iterator_to_array($input->getEnv()));
        $this->assertNotEmpty(iterator_to_array($input->getBasic()));
        $this->assertEmpty(iterator_to_array($input->getSystem()));

        $entries = $input->intoKeyObjectArray($input->getGet());
        $this->assertNotEmpty($entries);

        $entry = reset($entries);
        $this->assertEquals('foo', key($entries));
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('val1', $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('bar', key($entries));
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['bal1', 'bal2'], $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('baz', key($entries));
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('aff', key($entries));
        $this->assertEquals('aff', $entry->getKey());
        $this->assertEquals(42, $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_GET, $entry->getSource());
    }

    public function testFiles()
    {
        $source = new MockSource();
        $source->setRemotes($this->entryDataset(), null, null, $this->fileDataset());

        $input = new Inputs();
        $input->setSource($source)->loadEntries();

        $this->assertEmpty(iterator_to_array($input->getCli()));
        $this->assertNotEmpty(iterator_to_array($input->getGet()));
        $this->assertEmpty(iterator_to_array($input->getPost()));
        $this->assertEmpty(iterator_to_array($input->getSession()));
        $this->assertNotEmpty(iterator_to_array($input->getFiles()));
        $this->assertEmpty(iterator_to_array($input->getServer()));
        $this->assertEmpty(iterator_to_array($input->getEnv()));
        $this->assertNotEmpty(iterator_to_array($input->getBasic()));
        $this->assertEmpty(iterator_to_array($input->getSystem()));

        $entries = $input->intoKeyObjectArray($input->getFiles());
        $this->assertNotEmpty($entries);

        $entry = reset($entries);
        $this->assertEquals('files', key($entries));
        $this->assertEquals('files', $entry->getKey());
        $this->assertEquals('facepalm.jpg', $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('download[file1]', key($entries));
        $this->assertEquals('download[file1]', $entry->getKey());
        $this->assertEquals('MyFile.txt', $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('download[file2]', key($entries));
        $this->assertEquals('download[file2]', $entry->getKey());
        $this->assertEquals('MyFile.jpg', $entry->getValue());
        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $entry->getSource());
    }
}


class MockSource implements Sources\ISource
{
    protected $mockCli;
    protected $mockGet;
    protected $mockPost;
    protected $mockFiles;
    protected $mockSession;

    public function setRemotes(?array $get, ?array $post = null, ?array $cli = null, ?array $files = null, ?array $session = null): self
    {
        $this->mockCli = $cli;
        $this->mockGet = $get;
        $this->mockPost = $post;
        $this->mockFiles = $files;
        $this->mockSession = $session;
        return $this;
    }

    public function &cli(): ?array
    {
        return $this->mockCli;
    }

    public function &get(): ?array
    {
        return $this->mockGet;
    }

    public function &post(): ?array
    {
        return $this->mockPost;
    }

    public function &files(): ?array
    {
        return $this->mockFiles;
    }

    public function &session(): ?array
    {
        return $this->mockSession;
    }

    public function &server(): ?array
    {
        $content = null;
        return $content;
    }

    public function &env(): ?array
    {
        $content = null;
        return $content;
    }
}
