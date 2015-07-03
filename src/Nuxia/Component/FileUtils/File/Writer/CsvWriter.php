<?php

namespace Nuxia\Component\FileUtils\File\Writer;

use Nuxia\Component\FileUtils\Exception\FileCantWriteException;
use Nuxia\Component\FileUtils\File\CsvFile;

class CsvWriter extends AbstractWriter
{
    /**
     * @param CsvFile $file
     */
    public function __construct(CsvFile $file)
    {
        parent::__construct($file);
    }

    /**
     * @return CsvFile
     *
     * @throws \RuntimeException
     */
    public function getFile()
    {
        if (!$this->file instanceof CsvFile) {
            throw new \RuntimeException('File must be an instance of CsvFile');
        }

        return parent::getFile();
    }

    /**
     * {@inheritDoc}
     */
    public function setColumnsNames(array $columnsNames)
    {
        array_unshift($this->content, $columnsNames);
    }

    /**
     * @param array $content
     * @param array $columnsNames
     */
    public function appendContent(array $content, array $columnsNames = array())
    {
        //@TODO considerer l'ordre d'écriture pour bien remplir le content
        if (!is_resource($this->getFile()->getFilePointer())) {
            parent::setContent($content);
            $this->setColumnsNames($columnsNames);
        } else {
            array_push($this->content, $content);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write($content, $append = false)
    {
        $this->prepareWrite($append);
        if ($content) {
            $response = fputcsv(
                $this->file->getFilePointer(),
                array_values($content),
                $this->getFile()->getFieldDelimiter(),
                $this->getFile()->getFieldEnclosure()
            );

            if (false === $response) {
                throw new FileCantWriteException($this->getFile()->getPath());
            }
        }
    }
}