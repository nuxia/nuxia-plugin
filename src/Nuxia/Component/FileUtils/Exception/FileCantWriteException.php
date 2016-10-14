<?php

namespace Nuxia\Component\FileUtils\Exception;

class FileCantWriteException extends FileException
{
    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('Unable to write data in file %s', $path));
    }
}
