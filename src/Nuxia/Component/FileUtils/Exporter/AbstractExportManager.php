<?php

namespace Nuxia\Component\FileUtils\Exporter;

use Gedmo\Sluggable\Util\Urlizer;
use Nuxia\Component\FileUtils\File\FileInterface;

abstract class AbstractExportManager implements ExportManagerInterface
{
    /**
     * @param  FileInterface $file
     * @param  mixed         $data
     * @param  bool          $unaccent
     *
     * @return StreamedFileResponse
     *
     * @throws \RuntimeException
     */
    protected function createResponse(FileInterface $file, $data, $unAccent = true)
    {
        if (null === $file->getFilename()) {
            throw new \RuntimeException('Filename must be set');
        }
        $response = new StreamedFileResponse(Urlizer::urlize($file->getFilename()) . $this->getFileExtension());

        if (true === $unAccent) {
            foreach ($data as $rowIndex => $row) {
                foreach ($row as $dataIndex => $dataValue) {
                    $data[$rowIndex][$dataIndex] = Urlizer::unaccent($dataValue);

                }
            }
        }

        return $response->writeContent($file, $data);
    }
}
