<?php

namespace Nuxia\Component\FileUtils\File\Reader;

use Gedmo\Sluggable\Util\Urlizer;
use Nuxia\Component\FileUtils\Exception\FileIteratorMissingException;
use Nuxia\Component\FileUtils\Iterator\CsvFileIterator;

class CsvReader extends AbstractReader implements CsvReaderInterface
{
    /**
     * @return CsvFileIterator
     */
    public function getIterator()
    {
        if (!$this->iterator instanceof CsvFileIterator) {
            throw new \RuntimeException('Iterator must be an instance of CsvFileIteratorInterface');
        }

        return parent::getIterator();
    }

    /**
     * @param  callable $formatter
     *
     * @throws \Nuxia\Component\FileUtils\Exception\FileIteratorMissingException
     */
    public function setDynamicColumnNames(\Closure $formatter = null)
    {
        if (null === $this->iterator) {
            throw new FileIteratorMissingException($this);
        }

        if (null === $formatter) {
            $formatter = function ($element) {
                return trim(Urlizer::urlize($element, '_'));
            };
        }

        $buffer = array();

        foreach ($this->getIterator()->current() as $element) {
            $buffer[] = $formatter($element);
        }

        $this->setColumnNames($buffer);
    }

    /**
     * @param array $columnNames
     */
    public function setColumnNames(array $columnNames)
    {
        $this->getIterator()->setColumnNames($columnNames);
    }
}
