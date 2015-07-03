<?php

namespace Nuxia\Component;

interface ModelInterface
{
    /**
     * @param string $format
     *
     * @return string
     */
    public function getModelName($format = 'underscore');
}