<?php

namespace Imatic\Bundle\DataBundle;

use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\CommandHandlerCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\DriverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ImaticDataBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new DriverCompilerPass());
    }
}
