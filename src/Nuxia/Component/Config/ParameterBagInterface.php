<?php

namespace Nuxia\Component\Config;

/**
 * ParameterBagInterface.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
interface ParameterBagInterface
{
    /**
     * Returns a parameter by name.
     *
     * @param string $path    The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function get($path, $default = null);

    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters
     */
    public function add(array $parameters = []);

    /**
     * Sets a parameter by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set($key, $value);

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key);

    /**
     * Returns the number of parameters.
     *
     * @return int The number of parameters
     */
    public function count();

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     */
    public function remove($key);

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys();

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all();

    /**
     * This method has the same behavior as {@see \Nuxia\Component\Config\ParameterBagInterface::set}.
     * It just does nothing if the parameter has been set before.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setIfNotSet($key, $value);

    /**
     * This method has the same behavior as {@see \Nuxia\Component\Config\ParameterBagInterface::add}.
     * It only adds parameters which haven't been set before .
     *
     * @param array     $parameters
     * @param bool|null $override
     *
     * @return mixed
     */
    public function addIfNotSet(array $parameters = []);

    /**
     * Returns the parameters which match with the $keys parameter.
     * Not existing keys are ignored.
     *
     * @param array $keys
     *
     * @return array
     */
    public function filterByKeys(array $keys);
}
