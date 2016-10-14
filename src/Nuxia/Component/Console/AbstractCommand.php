<?php

namespace Nuxia\Component\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends SymfonyCommand
{
    //@REWORK a supprimer utilisé le trait à la place?
    /**
     * @var LoggerInterface
     */
    protected $logger;

    //@REWORK a supprimer les helpers sont directement ici à présent?
    /**
     * @param Helper $helper
     *
     * @return Helper
     */
    protected function addHelper(HelperInterface $helper)
    {
        $this->getHelperSet()->set($helper);

        return $helper;
    }

    //@REWORK a supprimer?
    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param OutputInterface $output
     * @param $text
     * @param string $style
     */
    protected function writeSummary(OutputInterface $output, $text, $style = 'bg=blue;fg=white')
    {
        $output->writeln([
            '',
            $this->getHelperSet()->get('formatter')->formatBlock($text, $style, true),
            '',
        ]);
    }
}
