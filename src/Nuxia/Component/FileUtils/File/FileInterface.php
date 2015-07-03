<?php

namespace Nuxia\Component\FileUtils\File;

interface FileInterface extends FileManipulatorInterface
{
    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string|null
     */
    public function getFilename();

    /**
     * @return resource
     */
    public function getFilePointer();
}