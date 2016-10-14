<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventDispatcherAwarePass implements CompilerPassInterface
{
    /**
     * @var string
     */
    const EVENT_DISPATCHER_AWARE_TAG = 'event_dispatcher.aware';

    /**
     * @var string
     */
    const EVENT_DISPATCHER_AWARE_INTERFACE = 'Nuxia\Component\EventDispatcherAwareInterface';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $eventDispatcher = new Reference('event_dispatcher');
        foreach ($container->findTaggedServiceIds(static::EVENT_DISPATCHER_AWARE_TAG) as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $refClass = new \ReflectionClass($definition->getClass());
            if (!$refClass->implementsInterface(static::EVENT_DISPATCHER_AWARE_INTERFACE)) {
                throw new \InvalidArgumentException(
                    sprintf('Service "%s" must implement interface "%s".', $id, static::EVENT_DISPATCHER_AWARE_INTERFACE)
                );
            }
            $definition->addMethodCall('setEventDispatcher', [$eventDispatcher]);
            $container->setDefinition($id, $definition);
        }
    }
}
