<?php

namespace Nuxia\Component\FileUtils\File;

use Nuxia\Component\FileUtils\Exception\FileNotFoundException;
use Nuxia\Component\FileUtils\File\Reader\ReaderInterface;
use Nuxia\Component\FileUtils\File\Writer\WriterInterface;
use Nuxia\Component\FileUtils\Iterator\FileIteratorInterface;

abstract class AbstractFile implements FileInterface
{
    /**
     * A full path to file that is being written / reader.
     *
     * @var string
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $filename;

    /**
     * @var FileIteratorInterface
     */
    protected $iterator = null;

    /**
     * @var resource
     *               Stream of current file
     */
    protected $filePointer;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @param string      $path
     * @param string|null $filename
     */
    public function __construct($path, $filename = null)
    {
        ini_set('auto_detect_line_endings', true);
        $this->path = $path;
        $this->filename = $filename;
    }

    /**
     * @param FileIteratorInterface $iterator
     */
    public function setIterator(FileIteratorInterface $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (!$this->exists()) {
            throw new FileNotFoundException($this->path);
        }

        return $this->iterator;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilePointer()
    {
        return $this->filePointer;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (is_resource($this->filePointer)) {
            fclose($this->filePointer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open($mode)
    {
        $this->filePointer = fopen($this->path, $mode);

        if (false === $this->filePointer) {
            throw new FileNotFoundException($this->path);
        }

        return $this->filePointer;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->close();

        if ($this->exists()) {
            unlink($this->path);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists()
    {
        return file_exists($this->path);
    }
}
