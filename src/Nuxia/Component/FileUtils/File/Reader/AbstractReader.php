<?php

namespace Nuxia\Component\FileUtils\File\Reader;

use Nuxia\Component\FileUtils\File\FileInterface;
use Nuxia\Component\FileUtils\Iterator\FileIteratorInterface;

abstract class AbstractReader
{
    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * @var \Traversable|null
     */
    protected $iterator = null;

    /**
     * @param FileInterface $file
     */
    public function __construct(FileInterface $file, FileIteratorInterface $iterator = null)
    {
        $this->file = $file;
        $this->iterator = $iterator === null ? $file->getIterator() : $iterator;
    }

    /**
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return null|\Traversable
     *
     * @throws \RuntimeException
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count(file($this->file->getPath()));
    }
}
