<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SecurityCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('nuxia.security.security.enabled')) {
            return;
        }

        $securityExtension = $container->getDefinition('twig.extension.security');
        $securityExtension->setClass('Nuxia\Bundle\NuxiaBundle\Twig\SecurityExtension');
        $securityExtension->addArgument(new Reference('nuxia.security.manager'));
        $securityExtension->addMethodCall('setSecurityManager', [new Reference('nuxia.security.manager')]);
    }
}
