<?php

namespace Nuxia\Component\Media;

interface UploadableInterface
{
    /**
     * @return string|null
     */
    public function getAbsolutePath();

    /**
     * @return string|null
     */
    public function getWebPath();

    /**
     * @return string
     */
    public function getUploadRootDir();

    /**
     * @return string
     */
    public function getUploadDir();

    /**
     * @return string
     */
    public function getPath();
    //public function setPath($path);
    //public function generatePath($path)
}