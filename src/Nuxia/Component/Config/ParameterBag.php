<?php

namespace Nuxia\Component\Config;

use Symfony\Component\HttpFoundation\ParameterBag as SymfonyParameterBag;

/**
 * Holds and manages parameters.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class ParameterBag extends SymfonyParameterBag implements ParameterBagInterface
{
    /**
     * {@inheritDoc}
     */
    public function setIfNotSet($key, $value)
    {
        if (!$this->has($key)) {
            parent::set($key, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addIfNotSet(array $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $this->setIfNotSet($key, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function filterByKeys(array $keys)
    {
        $return = array();

        foreach ($keys as $key) {
            if ($this->has($key)) {
                $return[$key] = $this->get($key);
            }
        }

        return $return;
    }
}