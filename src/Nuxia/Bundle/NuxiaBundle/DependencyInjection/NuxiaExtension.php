<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
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
        $this->loadSecurity($config, $container, $loader);
        $this->loadMedia($config, $container, $loader);
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
        $this->loadValidator($config, $container, $loader);
        $this->loadMailer($config, $container, $loader);
    }

    private function loadSecurity(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($config['security']['enabled']) {
            $container->setParameter('nuxia.security.disable_password', $config['security']['disable_password']);
            $loader->load('services/security.yml');
            //$this->loadLogin($config, $container, $loader);
        }
        unset($config['security']);
    }

    private function loadLogin(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($config['security']['login']['enabled']) {
            $loader->load('services/login.yml');
        }
        unset($config['security']['login']);
    }

    private function loadMedia(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setParameter('nuxia.media.thumbnail_path', $config['media']['thumbnail_path']);
        unset($config['media']);
    }

    private function loadMailer(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $from = $config['mailer']['from'];
        $container->setParameter('mailer.from', array($from['email'] => $from['name']));
        unset($config['email']['from']);
    }

    private function loadValidator(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/validator.yml');
        $container->setParameter('validator.reserved_words', $config['validator']['reserved_words']);
        unset($config['validator']);
    }
}
