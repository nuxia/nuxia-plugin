<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NuxiaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services/default.yml');
        $this->registerSecurityConfiguration($config, $container, $loader);
        $loader->load('services/doctrine.yml');
        $loader->load('services/form.yml');
        $loader->load('services/twig.yml');
        //@TODO: trouver un moyen qu'il s'ajoute dans le node par défaut (@defaultIfNotSet())
        if (!isset($config['mailer']['enabled']) || $config['mailer']['enabled'] === true) {
            $loader->load('services/mailer.yml');
        }
        //@TODO: trouver un moyen qu'il s'ajoute dans le node par défaut (@defaultIfNotSet())
        if (!isset($config['paginator']['enabled']) || $config['paginator']['enabled'] === true) {
            $loader->load('services/paginator.yml');
        }
        $this->registerValidatorConfiguration($config, $container, $loader);
        $this->registerMailerConfiguration($config, $container, $loader);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     */
    private function registerSecurityConfiguration(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($config['security']['enabled']) {
            $container->setParameter('nuxia.security.security.enabled', $config['security']['enabled']);
            $container->setParameter('nuxia.security.disable_password', $config['security']['disable_password']);
            $loader->load('services/security.yml');
            //$this->registerLoginConfiguration($config, $container, $loader);
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @pram   YamlFileLoader   $loader
     */
    private function registerLoginConfiguration(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($config['security']['login']['enabled']) {
            $loader->load('services/login.yml');
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @pram   YamlFileLoader   $loader
     */
    private function registerMailerConfiguration(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $from = $config['mailer']['from'];
        $container->setParameter('mailer.from', array($from['email'] => $from['name']));
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @pram   YamlFileLoader   $loader
     */
    private function registerValidatorConfiguration(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/validator.yml');
        $container->setParameter('validator.reserved_words', $config['validator']['reserved_words']);
    }
}
