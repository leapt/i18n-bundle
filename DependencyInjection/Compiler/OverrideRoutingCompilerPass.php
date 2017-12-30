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
        $container->setAlias('router', 'leapt_i18n.routing_router')
            ->setPublic(true);
    }
}