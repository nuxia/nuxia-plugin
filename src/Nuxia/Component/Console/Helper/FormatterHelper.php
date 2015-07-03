<?php

namespace Nuxia\Component\Console\Helper;

use Symfony\Component\Console\Helper\FormatterHelper as SymfonyFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

//@DEPRECATED : Utiliser AbstractInteractiveCommand
class FormatterHelper extends SymfonyFormatterHelper
{
    public function writeSummary(OutputInterface $output, $text, $style = 'bg=blue;fg=white')
    {
        $output->writeln(array(
            '',
            $this->formatBlock($text, $style, true),
            '',
        ));
    }
}
