<?php

namespace Nuxia\Component;

use Nuxia\Component\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Yaml\Yaml;

class Parser
{
    /**
     * @param string      $bundle
     * @param string      $file
     * @param null|string $path
     *
     * @return array
     */
    public static function parseYaml($bundle, $file, $path = null)
    {
        $buffer = Yaml::parse(FileLoader::getFileContents($bundle, $file));
        if ($path !== null) {
            $keys = explode('.', $path);
            foreach ($keys as $path) {
                $buffer = $buffer[$path];
            }
        }

        return $buffer;
    }

    /**
     * @param string $bundle
     * @param string $file
     * @param string $path
     *
     * @return array
     */
    public static function getValuelist($bundle, $file, $path)
    {
        return Parser::parseYaml($bundle, 'Resources/config/valuelist/' . $file . '.yml', $path);
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    public static function getBundleClass($namespace)
    {
        $buffer = explode('\\', $namespace);
        return $buffer[0] . '\\' . $buffer[1] . '\\' . $buffer[0] . $buffer[1];
    }

    /**
     * @param array       $array
     * @param null|string $prefix
     * @param string      $type
     *
     * @return array
     */
    public static function prefixArray(array $array, $prefix = null, $type = 'ARRAY_ASSOC')
    {
        if ($prefix !== null) {
            $callback = function ($v) use ($prefix) { return $prefix . '.' . $v; };
            $prefixedArray = array_map($callback, array_values($array));
            switch ($type) {
                case 'ARRAY_ASSOC':
                    $keys = Parser::isAssociativeArray($array) ? array_keys($array) : $array;
                    return array_combine($keys, $prefixedArray);
                    break;
                default:
                    return $prefixedArray;
                    break;
            }

            return $prefixedArray;
        }

        return $array;

    }

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function isAssociativeArray(array $array)
    {
        return (is_array($array) && count(array_filter(array_keys($array), 'is_string')) == count($array));
    }

    /**
     * @param array  $array
     * @param string $type
     *
     * @return array
     */
    public static function cleanArray(array $array, $type = 'default')
    {
        switch ($type) {
            case 'all':
                $callback = function ($v) {
                    return !($v === null || $v === '' || $v === array());
                };
                break;
            default:
                $callback = function ($v) {
                    return !($v === null || $v === '');
                };
        }
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = Parser::cleanArray($value);
            }
        }

        return array_filter($array, $callback);
    }

    //@TODO se détacher de la dépendance avec le Container de Symfony?
    /**
     * @param string $string
     *
     * @return string
     */
    public static function camelize($string)
    {
        return Container::camelize($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function underscore($string)
    {
        return Container::underscore($string);
    }

    /**
     * @param array $array
     * @param array $keys
     *
     * @return array
     */
    public static function filterArrayByKeys(array $array, array $keys)
    {
        return array_intersect_key($array, array_fill_keys($keys, 'buffer'));
    }

    //@TODO mettre en place dans field et router (a remplacer par property path?)
    /**
     * @param object $object
     * @param string $path
     * @param string $pathSeparator
     *
     * @return string
     */
    public static function getValueFromPath($object, $path, $pathSeparator = '.')
    {
        $value = $object;
        foreach (explode($pathSeparator, $path) as $segment) {
            $method = 'get' . Parser::camelize($segment);
            if (method_exists($value, $method)) {
                $value = $value->$method();
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * @param \Datetime $datetime
     *
     * @return array
     */
    public static function datetimeToFilter(\Datetime $datetime)
    {
        $date = $datetime->format('Y/m/d');

        return array('operator' => 'between', 'start' => $date . ' 00:00', 'end' => $date . ' 23:59');
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    public static function pickRandomValue(array $array)
    {
        return $array[array_rand($array)];
    }
}
