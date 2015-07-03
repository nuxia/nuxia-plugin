<?php

namespace Nuxia\Component\Console;

class Validators
{
    /**
     * @param  string $input
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function validateNotBlank($input)
    {
        if (empty($input) || $input === null) {
            throw new \InvalidArgumentException('The input is mandatory.');
        }
        return $input;
    }
}
