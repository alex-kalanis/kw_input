<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Loaders;


class FileTest extends CommonTestClass
{
    public function testPass(): void
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
}
