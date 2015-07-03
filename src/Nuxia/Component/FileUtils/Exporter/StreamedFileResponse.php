<?php

namespace Nuxia\Component\FileUtils\Exporter;

use Nuxia\Component\FileUtils\File\FileInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamedFileResponse extends StreamedResponse
{
    /**
     * @param string $filename
     * @param int    $status
     */
    public function __construct($filename, $status = 200)
    {
        parent::__construct(null, $status);
        $this->buildHeaders($filename);
    }

    /**
     * @param FileInterface $file
     * @param mixed         $content
     *
     * @return $this
     */
    public function writeContent(FileInterface $file, $content)
    {
        $this->setCallback(function() use ($file, $content) {
            foreach ($content as $row) {
                $file->getWriter()->write($row);
            }
        });
        return $this;
    }

    /**
     * @param string $filename
     */
    private function buildHeaders($filename)
    {
        $this->headers->add(array(
            'Content-Type' =>  'application/force-download',
            'Content-Disposition' => $this->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename
            ),
        ));
    }
}
