<?php

namespace kalanis\kw_input\Inputs;


/**
 * Class FileEntry
 * @package kalanis\kw_input\Inputs
 * Input is file and has extra values
 */
class FileEntry extends Entry implements IFileEntry
{
    protected $mimeType = '';
    protected $tmpName = '';
    protected $error = 0;
    protected $size = 0;

    public function setFile(string $fileName, string $tmpName, string $mimeType, int $error, int $size): self
    {
        $this->value = $fileName;
        $this->mimeType = $mimeType;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
        return $this;
    }

    public function getSource(): string
    {
        return static::SOURCE_FILES;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getTempName(): string
    {
        return $this->tmpName;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
