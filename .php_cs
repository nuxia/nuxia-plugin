<?php

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
       'ordered_use',
       'concat_with_spaces',
       'short_array_syntax',
       'empty_return',
       'phpdoc_order',
       'multiline_spaces_before_semicolon'
    ])
    ->finder(Symfony\CS\Finder\DefaultFinder::create()
        ->exclude(['vendor'])
        ->in(__DIR__)
    )
;
