<?php

namespace Nuxia\Component\FileUtils\File\Writer;

interface WriterInterface extends \Countable
{
    /**
     * @param mixed $content
     * @param bool  $append
     */
    public function write($content, $append = false);
}
