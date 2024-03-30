<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_input\Parsers;


class FilesTest extends CommonTestClass
{
    public function testPass(): void
    {
        $data = new Parsers\Files();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->fileDataset();
        $entries = $data->parseInput($dataset);

        $this->assertEquals($dataset, $entries);
    }

    public function testStrange(): void
    {
        $data = new Parsers\Files();
        $this->assertInstanceOf(Parsers\AParser::class, $data);

        $dataset = $this->strangeFileDataset();
        $entries = $data->parseInput($dataset);

        $entry = reset($entries);
        $this->assertEquals('files', key($entries));
        $this->assertEquals([ // simple upload
            'name' => 'facepalm.jpg',
            'type' => 'image<?= \'/\'; ?>jpeg',
            'tmp_name' => '/tmp/php3zU3t5',
            'error' => UPLOAD_ERR_OK,
            'size' => '591387',
        ], $entry);

        $entry = next($entries);
        $this->assertEquals('download', key($entries));
        $this->assertEquals([
            'file1' => 'C:\System\MyFile.txt',
            'file2' => 'A:\MyFile.jpg',
        ], $entry['name']);
    }
}
