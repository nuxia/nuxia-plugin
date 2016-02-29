<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FlashBagPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $flashBag = $container->getDefinition('session.flash_bag');
        $flashBag->setClass('Nuxia\Component\HttpFoundation\Session\Flash\FlashBag');
    }
}
