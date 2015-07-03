<?php

namespace Nuxia\Component\FileUtils\Exporter;

interface ExportManagerInterface
{
    /**
     * @param  array       $data
     * @param  string|null $filename
     *
     * @return StreamedFileResponse
     */
    public function export($data, $filename = null);

    /**
     * @return string
     */
    public function getFileExtension();
}
