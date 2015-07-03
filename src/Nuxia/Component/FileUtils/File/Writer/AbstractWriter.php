<?php

namespace Nuxia\Component\FileUtils\File\Writer;

use Nuxia\Component\FileUtils\File\FileInterface;
use Nuxia\Component\FileUtils\File\FileManipulatorInterface;

abstract class AbstractWriter implements WriterInterface
{
    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * @var array
     */
    protected $content = array();

    /**
     * @param FileInterface $file
     */
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }

    /**
     * @return FileInterface
     *
     * @throws \RuntimeException
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return array
     */
    public function setContent(array $content)
    {
        $this->content = $content;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->content);
    }

    /**
     * @param bool $append
     */
    protected function prepareWrite($append)
    {
        if (!is_resource($this->file->getFilePointer())) {
            $mode = $append ?
                FileManipulatorInterface::BOTTOM_TO_TOP_READ_ONLY :
                FileManipulatorInterface::TOP_TO_BOTTOM_READ_ONLY_OR_CREATE
            ;
            $this->file->open($mode);
        }
    }
}
