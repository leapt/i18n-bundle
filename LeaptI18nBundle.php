<?php

namespace Leapt\I18nBundle;

use Leapt\I18nBundle\DependencyInjection\Compiler\OverrideRoutingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeaptI18nBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideRoutingCompilerPass());
    }
}
