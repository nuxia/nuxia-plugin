<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $twigFormExtension = $container->getDefinition('twig.extension.form');
        $twigFormExtension->setClass('Nuxia\Bundle\NuxiaBundle\Twig\FormExtension');
    }
}
