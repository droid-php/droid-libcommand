<?php

namespace Droid\Lib\Plugin\Model\File;

use DomainException;

class UnusableFileException extends DomainException
{
    private $unusableFile;

    public function setUnusableFile($file)
    {
        $this->unusableFile = $file;
    }

    public function getUnusableFile()
    {
        return $this->unusableFile;
    }
}
