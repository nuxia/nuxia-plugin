<?php

namespace Nuxia\Bundle\NuxiaBundle;

use Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler\EventDispatcherAwarePass;
use Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler\FormPass;
use Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler\SecurityCompilerPass;
use Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler\FlashBagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NuxiaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new EventDispatcherAwarePass());
        $container->addCompilerPass(new SecurityCompilerPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new FlashBagPass());
    }
}
