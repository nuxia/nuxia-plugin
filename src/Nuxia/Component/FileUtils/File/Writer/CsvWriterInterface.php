<?php

namespace Nuxia\Component\FileUtils\File\Writer;

interface CsvWriterInterface extends WriterInterface
{
    public function write(array $content, array $columnsNames = [], $append = false);
    public function setColumnsNames(array $columnsNames);
}
