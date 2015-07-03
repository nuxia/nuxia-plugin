<?php

namespace Nuxia\Component\Loader;

use Nuxia\Component\Parser;

class FileLoader
{
    /**
     * @param string $bundle
     * @param string $filepath
     *
     * @return string
     */
    public static function getFilePath($bundle, $filepath)
    {
        $bundleclass = Parser::getBundleClass($bundle);
        $reflection = new \ReflectionClass($bundleclass);
        return dirname($reflection->getFileName()) . '/' . $filepath;
    }

    /**
     * @param string $bundle
     * @param string $filepath
     *
     * @return string
     */
    public static function getFileContents($bundle, $filepath)
    {
        return file_get_contents(FileLoader::getFilePath($bundle, $filepath));
    }
}