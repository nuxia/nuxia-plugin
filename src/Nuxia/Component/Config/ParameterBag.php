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
     * {@inheritdoc}
     */
    public function setIfNotSet($key, $value)
    {
        if (!$this->has($key)) {
            parent::set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addIfNotSet(array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $this->setIfNotSet($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function filterByKeys(array $keys)
    {
        $return = [];

        foreach ($keys as $key) {
            if ($this->has($key)) {
                $return[$key] = $this->get($key);
            }
        }

        return $return;
    }
}
