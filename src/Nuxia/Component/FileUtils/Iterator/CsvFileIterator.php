<?php

namespace Nuxia\Component\FileUtils\Iterator;

use Nuxia\Component\FileUtils\File\CsvFile;
use Nuxia\Component\FileUtils\File\FileManipulatorInterface;

class CsvFileIterator extends FileIterator
{
    /**
     * @var array
     */
    protected $names;

    /**
     * @var bool
     */
    protected $ignoreFirstLine;

    /**
     * @param string $path
     * @param string $delimiter
     * @param string $fieldEnclosure
     * @param string $escapeChar
     */
    public function __construct($path, $delimiter = ';', $fieldEnclosure = '"', $escapeChar = '\\')
    {
        parent::__construct($path, FileManipulatorInterface::TOP_TO_BOTTOM_READ_ONLY);
        $this->setFlags(self::READ_CSV);
        $this->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
    }

    /**
     * {@inheritdoc}
     */
    public function setColumnNames(array $names)
    {
        $this->names = $names;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $row = parent::current();
        if ($this->key() === 0 && $this->ignoreFirstLine === true) {
            $this->next();
        }
        if ($this->names) {
            if (count($row) != count($this->names)) {
                return;
            } else {
                $row = array_combine($this->names, $row);
            }
        }

        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $current = $this->current();

        if ($this->names) {
            return count($current) == count($this->names);
        }

        return parent::valid();
    }

    /**
     * @param CsvFile $csvFile
     *
     * @return CsvFileIterator
     */
    public static function createFromFile(CsvFile $csvFile)
    {
        return new self($csvFile->getPath(), $csvFile->getFieldDelimiter(), $csvFile->getFieldEnclosure());
    }

    /**
     * @return CsvFileIterator
     */
    public function ignoreFirstLine()
    {
        $this->ignoreFirstLine = true;

        return $this;
    }
}
