<?php

namespace FilteredTests;


use CommonTestClass;
use kalanis\kw_input\Entries;
use kalanis\kw_input\Filtered;
use kalanis\kw_input\Inputs;
use kalanis\kw_input\Interfaces;


class VariablesTest extends CommonTestClass
{
    public function testBasics(): void
    {
        $input = new Inputs();
        $input->setSource($this->cliDataset()); // direct cli

        $source = new MockSource();
        $source->setRemotes($this->entryDataset(), null, $this->cliDataset());
        $variables = new MockVariables($input->setSource($source)->loadEntries());

        $this->assertNotEmpty($variables->getCli());
        $this->assertNotEmpty($variables->getGet());
        $this->assertEmpty($variables->getPost());
        $this->assertEmpty($variables->getSession());
        $this->assertEmpty($variables->getCookie());
        $this->assertNotEmpty($variables->getFiles()); // seems strange, but there are files from Cli
        $this->assertEmpty($variables->getServer());
        $this->assertEmpty($variables->getEnv());
        $this->assertNotEmpty($variables->getBasic());
        $this->assertEmpty($variables->getSystem());
        $this->assertEmpty($variables->getExternal());

        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_GET]);
        $this->assertNotEmpty($entries);

        $entry = reset($entries);
        $this->assertEquals('foo', key($entries));
        $this->assertEquals('foo', $entry->getKey());
        $this->assertEquals('val1', $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('bar', key($entries));
        $this->assertEquals('bar', $entry->getKey());
        $this->assertEquals(['bal1', 'bal2'], $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('baz', key($entries));
        $this->assertEquals('baz', $entry->getKey());
        $this->assertEquals(true, $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('aff', key($entries));
        $this->assertEquals('aff', $entry->getKey());
        $this->assertEquals(42, $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $entry->getSource());
    }

    public function testFiles(): void
    {
        $source = new MockSource();
        $source->setRemotes($this->entryDataset(), null, null, $this->fileDataset());

        $variables = new MockVariables((new Inputs())->setSource($source)->loadEntries());

        $this->assertEmpty($variables->getCli());
        $this->assertNotEmpty($variables->getGet());
        $this->assertEmpty($variables->getPost());
        $this->assertEmpty($variables->getSession());
        $this->assertNotEmpty($variables->getFiles());
        $this->assertEmpty($variables->getCookie());
        $this->assertEmpty($variables->getServer());
        $this->assertEmpty($variables->getEnv());
        $this->assertNotEmpty($variables->getBasic());
        $this->assertEmpty($variables->getSystem());
        $this->assertEmpty($variables->getExternal());

        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_FILES]);
        $this->assertNotEmpty($entries);

        $entry = reset($entries);
        $this->assertEquals('files', key($entries));
        $this->assertEquals('files', $entry->getKey());
        $this->assertEquals('facepalm.jpg', $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_FILES, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('download[file1]', key($entries));
        $this->assertEquals('download[file1]', $entry->getKey());
        $this->assertEquals('MyFile.txt', $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_FILES, $entry->getSource());

        $entry = next($entries);
        $this->assertEquals('download[file2]', key($entries));
        $this->assertEquals('download[file2]', $entry->getKey());
        $this->assertEquals('MyFile.jpg', $entry->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_FILES, $entry->getSource());
    }

    public function testObject(): void
    {
        $input = new Inputs();
        $input->setSource($this->cliDataset()); // direct cli

        $source = new MockSource();
        $source->setRemotes($this->entryDataset());
        $variables = new MockVariables($input->setSource($source)->loadEntries());

        $this->assertNotEmpty($variables->getGet());

        /** @var Interfaces\IEntry[] $entries */
        $entries = $variables->getInArray(null, [Interfaces\IEntry::SOURCE_GET]);
        $input = new Filtered\FilterAdapter($variables, [Interfaces\IEntry::SOURCE_GET]);
        $this->assertEquals(4, $input->count());
        $this->assertNotEmpty(count($entries));

        $this->assertTrue(isset($input['foo']));
        $this->assertEquals('foo', $input['foo']->getKey());
        $this->assertEquals('val1', $input['foo']->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input['foo']->getSource());

        $this->assertTrue($input->offsetExists('bar'));
        $this->assertEquals('bar', $input->offsetGet('bar')->getKey());
        $this->assertEquals(['bal1', 'bal2'], $input->offsetGet('bar')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->offsetGet('bar')->getSource());

        $this->assertTrue(isset($input->baz));
        $this->assertEquals('baz', $input->baz->getKey());
        $this->assertEquals(true, $input->baz->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->baz->getSource());

        $this->assertTrue($input->offsetExists('aff'));
        $this->assertEquals('aff', $input->offsetGet('aff')->getKey());
        $this->assertEquals(42, $input->offsetGet('aff')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_GET, $input->offsetGet('aff')->getSource());

        $this->assertFalse($input->offsetExists('uhb'));
        $input->offsetSet('uhb', 'feaht');
        $this->assertEquals('feaht', $input->offsetGet('uhb')->getValue());
        $this->assertEquals(Interfaces\IEntry::SOURCE_EXTERNAL, $input->offsetGet('uhb')->getSource());

        $entry = $input->offsetGet('aff');
        unset($input['aff']);
        $this->assertFalse(isset($input['aff']));
        $input[$entry->getKey()] = $entry;
        $this->assertTrue($input->offsetExists('aff'));
        $input[$entry->getKey()] = 'tfc';
        $this->assertEquals('tfc', $input->offsetGet('aff'));

        $entry = $input->baz;
        unset($input->baz);
        $this->assertTrue(empty($input->baz));
        $input->{$entry->getKey()} = $entry;
        $this->assertTrue(isset($input->baz));
    }
}


class MockSource implements Interfaces\ISource
{
    protected ?array $mockCli = null;
    protected ?array $mockGet = null;
    protected ?array $mockPost = null;
    protected ?array $mockFiles = null;
    protected ?array $mockCookie = null;
    protected ?array $mockSession = null;

    public function setRemotes(?array $get, ?array $post = null, ?array $cli = null, ?array $files = null, ?array $cookie = null, ?array $session = null): self
    {
        $this->mockCli = $cli;
        $this->mockGet = $get;
        $this->mockPost = $post;
        $this->mockFiles = $files;
        $this->mockCookie = $cookie;
        $this->mockSession = $session;
        return $this;
    }

    public function cli(): ?array
    {
        return $this->mockCli;
    }

    public function get(): ?array
    {
        return $this->mockGet;
    }

    public function post(): ?array
    {
        return $this->mockPost;
    }

    public function files(): ?array
    {
        return $this->mockFiles;
    }

    public function cookie(): ?array
    {
        return $this->mockCookie;
    }

    public function session(): ?array
    {
        return $this->mockSession;
    }

    public function server(): ?array
    {
        $content = null;
        return $content;
    }

    public function env(): ?array
    {
        $content = null;
        return $content;
    }

    public function external(): ?array
    {
        $content = null;
        return $content;
    }

    public function inputRawPaths(): ?array
    {
        return [];
    }
}


class MockVariables extends Filtered\Variables
{
    public function getBasic(): array
    {
        return $this->getInArray(null, [
            Interfaces\IEntry::SOURCE_CLI,
            Interfaces\IEntry::SOURCE_GET,
            Interfaces\IEntry::SOURCE_POST,
        ]);
    }

    public function getSystem(): array
    {
        return $this->getInArray(null, [
            Interfaces\IEntry::SOURCE_SERVER,
            Interfaces\IEntry::SOURCE_ENV,
        ]);
    }

    public function getCli(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_CLI]);
    }

    public function getGet(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_GET]);
    }

    public function getPost(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_POST]);
    }

    public function getSession(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_SESSION]);
    }

    public function getCookie(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_COOKIE]);
    }

    public function getFiles(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_FILES]);
    }

    public function getServer(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_SERVER]);
    }

    public function getEnv(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_ENV]);
    }

    public function getExternal(): array
    {
        return $this->getInArray(null, [Interfaces\IEntry::SOURCE_EXTERNAL]);
    }
}


class ExEntry extends Entries\Entry
{
    public static function init(string $source, string $key, $value = null): Entries\Entry
    {
        $lib = new self();
        $lib->setEntry($source, $key, $value);
        return $lib;
    }
}
