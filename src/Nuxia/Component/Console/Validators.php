<?php

namespace Nuxia\Component\Console;

class Validators
{
    /**
     * @param string $input
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function validateNotBlank($input)
    {
        if (empty($input) || $input === null) {
            throw new \InvalidArgumentException('The input is mandatory.');
        }

        return $input;
    }
}
