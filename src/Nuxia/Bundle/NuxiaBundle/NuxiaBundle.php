<?php

namespace Nuxia\Bundle\NuxiaBundle;

use Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler\EventDispatcherAwarePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NuxiaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new EventDispatcherAwarePass());
    }
}
