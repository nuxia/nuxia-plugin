<?php

namespace Nuxia\Component\FileUtils\File;

use Nuxia\Component\FileUtils\File\Reader\ReaderInterface;
use Nuxia\Component\FileUtils\File\Writer\WriterInterface;
use Nuxia\Component\FileUtils\Iterator\FileIteratorInterface;

interface FileManipulatorInterface
{
    /**
     * @var string
     */
    const TOP_TO_BOTTOM_READ_ONLY = 'r';

    /**
     * @var string
     */
    const TOP_TO_BOTTOM_READ_WRITE = 'r+';

    /**
     * @var string
     */
    const TOP_TO_BOTTOM_READ_ONLY_OR_CREATE = 'w';

    /**
     * @var string
     */
    const TOP_TO_BOTTOM_READ_WRITE_OR_CREATE = 'w+';

    /**
     * @var string
     */
    const BOTTOM_TO_TOP_READ_ONLY = 'a';

    /**
     * @var string
     */
    const BOTTOM_TO_TOP_READ_WRITE = 'a+';

    /**
     * @var string
     */
    const BOTTOM_TO_TOP_READ_ONLY_OR_CREATE = 'x';

    /**
     * @var string
     */
    const BOTTOM_TO_TOP_READ_WRITE_OR_CREATE = 'x+';

    /**
     * @throws \RuntimeException
     *
     * @return \Traversable|null|FileIteratorInterface
     */
    public function getIterator();

    /**
     * @return ReaderInterface
     */
    public function getReader();

    /**
     * @return WriterInterface
     */
    public function getWriter();

    public function close();

    /**
     * @param string $mode
     *
     * @throws \RuntimeException
     *
     * @return ressource
     */
    public function open($mode);

    /**
     * @return bool
     */
    public function exists();

    public function delete();
}
