<?php

use kalanis\kw_input\Entries;


class EntryTest extends CommonTestClass
{
    public function testEntry()
    {
        $data = new Entries\Entry();
        $this->assertInstanceOf('\kalanis\kw_input\Entries\IEntry', $data);

        $this->assertEmpty($data->getSource());
        $this->assertEmpty($data->getKey());
        $this->assertEmpty($data->getValue());

        $data->setEntry('different', 'foz', 'wuz');
        $this->assertEmpty($data->getSource());
        $this->assertEquals('foz', $data->getKey());
        $this->assertEquals('wuz', $data->getValue());

        $data->setEntry(Entries\IEntry::SOURCE_GET, 'ugg', 'huu');
        $this->assertEquals(Entries\IEntry::SOURCE_GET, $data->getSource());
        $this->assertEquals('ugg', $data->getKey());
        $this->assertEquals('huu', $data->getValue());

        $data->setEntry(Entries\IEntry::SOURCE_POST, 'aqq');
        $this->assertEquals(Entries\IEntry::SOURCE_POST, $data->getSource());
        $this->assertEquals('aqq', $data->getKey());
        $this->assertEmpty($data->getValue());
    }

    public function testFile()
    {
        $data = new Entries\FileEntry();
        $this->assertInstanceOf('\kalanis\kw_input\Entries\IEntry', $data);
        $this->assertInstanceOf('\kalanis\kw_input\Entries\IFileEntry', $data);

        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $data->getSource());
        $this->assertEmpty($data->getKey());
        $this->assertEmpty($data->getValue());
        $this->assertEmpty($data->getMimeType());
        $this->assertEmpty($data->getTempName());
        $this->assertEmpty($data->getError());
        $this->assertEmpty($data->getSize());

        $data->setEntry('different', 'foz', 'wuz');
        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $data->getSource());
        $this->assertEquals('foz', $data->getKey());
        $this->assertEquals('wuz', $data->getValue());
        $this->assertEmpty($data->getMimeType());
        $this->assertEmpty($data->getTempName());
        $this->assertEmpty($data->getError());
        $this->assertEmpty($data->getSize());

        $data->setEntry(Entries\IEntry::SOURCE_GET, 'ugg', 'huu');
        $data->setFile('foo', 'uff', 'octet', 15, 20);
        $this->assertEquals(Entries\IEntry::SOURCE_FILES, $data->getSource());
        $this->assertEquals('ugg', $data->getKey());
        $this->assertEquals('foo', $data->getValue()); // yep, value is file name
        $this->assertEquals('uff', $data->getTempName());
        $this->assertEquals('octet', $data->getMimeType());
        $this->assertEquals(15, $data->getError());
        $this->assertEquals(20, $data->getSize());
    }
}
