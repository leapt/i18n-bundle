<?php

namespace Leapt\I18nBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRoutingCompilerPass implements CompilerPassInterface {
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('router.class', 'Leapt\I18nBundle\Routing\I18nRouter');
        $container->getDefinition('router.default')->replaceArgument(4, $container->getDefinition('leapt_i18n'));
    }
}