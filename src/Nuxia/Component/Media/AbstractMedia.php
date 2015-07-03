<?php

namespace Nuxia\Component\Media;

use Nuxia\Component\AbstractModel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractMedia extends AbstractModel implements UploadableInterface
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var string
     */
    protected $filenameToRemove;

    /**
     * @var bool
     */
    protected $removed;

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;

        if (!$this->isNew()) {
            $this->filenameToRemove = $this->getAbsolutePath();
        }
    }

    /**
     *
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $this->removeUpload();
        $this->file->move($this->getUploadRootDir(), $this->getPath());
        $this->detachFile();
    }

    /**
     * @param string $destination
     */
    public function copyFile($destination)
    {
        $filesytem = new Filesystem();
        $filesytem->copy($this->getAbsolutePath(), $destination);
    }

    /**
     *
     */
    public function storeFilenameForRemove()
    {
        $this->filenameToRemove = $this->getAbsolutePath();
    }

    /**
     *
     */
    public function removeUpload()
    {
        if ($this->filenameToRemove !== null) {
            unlink($this->filenameToRemove);
            $this->filenameToRemove = null;
        }
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return file_exists($this->getAbsolutePath()) && exif_imagetype($this->getAbsolutePath());
    }

    /**
     * @return string
     */
    public function detectMimeType()
    {
        if (class_exists('finfo', false)) {
            $const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
            $mime = finfo_file(finfo_open($const), $this->getAbsolutePath());
        }
        if (empty($result) && (function_exists('mime_content_type') && ini_get('mime_magic.magicfile'))) {
            $mime = mime_content_type($this->getAbsolutePath());
        }
        if (empty($mime)) {
            $mime = 'application/octet-stream';
        }

        return $mime;
    }

    /**
     * {@inheritdoc}
     */
    public function getAbsolutePath()
    {
        return null === $this->getPath() ? null : $this->getUploadRootDir() . '/' . $this->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getWebPath()
    {
        return null === $this->getPath() ? null : $this->getUploadDir() . '/' . $this->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadRootDir()
    {
        $reflection = new \ReflectionClass('AppKernel');
        // the absolute directory data where uploaded documents should be saved
        return dirname($reflection->getFilename()) . '/../web/' . $this->getUploadDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads';
    }

    /**
     * @param bool $removed
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;
    }

    /**
     * @return bool
     */
    public function isRemoved()
    {
        return $this->removed;
    }

    /**
     * @param bool $withExtension
     * @param null $limit
     *
     * @return null|string
     */
    public function getFilename($withExtension = false, $limit = null)
    {
        $file = $this->getFile();
        if ($file !== null) {
            if ($file instanceof UploadedFile) {
                $fileName = $file->getClientOriginalName();
            } elseif ($file instanceof File) {
                $fileName = $file->getFilename();
            }
            $fileName = substr($fileName, 0, strpos($fileName, '.'));
            if ($limit !== null && strlen($fileName) >= $limit) {
                $fileName = substr($fileName, 0, $limit);
            }

            return $fileName;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->getId() === null;
    }

    /**
     *
     */
    public function detachFile()
    {
        $this->file = null;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return substr($this->getPath(), strrpos($this->getPath(), '.') + 1);
    }
}