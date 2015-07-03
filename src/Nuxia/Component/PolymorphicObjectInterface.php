<?php

namespace Nuxia\Component;

interface PolymorphicObjectInterface
{
    /**
     * @return array
     */
    public function toPolymorphicCriteria();
}