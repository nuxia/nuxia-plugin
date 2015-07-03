<?php
namespace Nuxia\Component\FileUtils\File;

use Nuxia\Component\FileUtils\File\Reader\CsvReader;
use Nuxia\Component\FileUtils\File\Writer\CsvWriter;

class CsvFile extends AbstractFile
{
    /**
     * @var string
     */
    protected $fieldDelimiter = ";";

    /**
     * @var string
     */
    protected $fieldEnclosure = '"';

    /**
     * @var string
     */
    protected $lineDelimiter = '\n';

    /**
     * @return string
     */
    public function getFieldDelimiter()
    {
        return $this->fieldDelimiter;
    }

    /**
     * @param string $fieldDelimiter
     */
    public function setFieldDelimiter($fieldDelimiter)
    {
        $this->fieldDelimiter = $fieldDelimiter;
    }

    /**
     * @return string
     */
    public function getFieldEnclosure()
    {
        return $this->fieldEnclosure;
    }

    /**
     * @param string $fieldEnclosure
     */
    public function setFieldEnclosure($fieldEnclosure)
    {
        $this->fieldEnclosure = $fieldEnclosure;
    }

    /**
     * @return string
     */
    public function getLineDelimiter()
    {
        return $this->lineDelimiter;
    }

    /**
     * @param string $lineDelimiter
     */
    public function setLineTerminator($lineDelimiter)
    {
        $this->lineTerminator = $lineDelimiter;
    }

    /**
     * {@inheritDoc}
     */
    public function getWriter()
    {
        if ($this->writer === null) {
            $this->writer = new CsvWriter($this);
        }
        return $this->writer;
    }

    /**
     * {@inheritDoc}
     */
    public function getReader()
    {
        if ($this->reader === null) {
            $this->reader = new CsvReader($this);
        }
        return $this->reader;
    }
}