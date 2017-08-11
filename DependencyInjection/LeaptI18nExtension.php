<?php

namespace Leapt\I18nBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LeaptI18nExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('leapt_i18n.locales', $config['locales']);

        // Routing
        if (PHP_VERSION_ID < 70000) {
            $this->addClassesToCompile(['Leapt\I18nBundle\Routing\I18nRouter']);
        }
        foreach (['translation_domain'] as $parameter) {
            $container->setParameter('leapt_i18n.routing.' . $parameter, $config['routing'][$parameter]);
        }
    }
}
