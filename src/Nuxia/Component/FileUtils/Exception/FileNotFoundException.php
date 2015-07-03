<?php

namespace Nuxia\Component\FileUtils\Exception;

class FileNotFoundException extends FileException
{
    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The file "%s" does not exist', $path));
    }
} 