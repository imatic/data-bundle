<?php

namespace Imatic\Bundle\DataBundle;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type\FileType;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\CommandHandlerCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\ContainsOperatorProcessorCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\DisplayCriteriaQueryBuilderPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\DriverCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\FilterCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\FilterRuleProcessorPass;
use Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST\UnaccentLower;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ImaticDataBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ContainsOperatorProcessorCompilerPass());
        $container->addCompilerPass(new FilterCompilerPass());
        $container->addCompilerPass(new FilterRuleProcessorPass());
        $container->addCompilerPass(new DisplayCriteriaQueryBuilderPass());
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new DriverCompilerPass());
    }

    public function boot()
    {
        $basePath = $this->container->getParameter('imatic_data.base_file_path');
        FileType::setBasePath($basePath);

        if ($this->container->hasParameter('imatic_data.doctrine.contains_operator_processor.function')) {
            UnaccentLower::setFunction(
                $this->container->getParameter('imatic_data.doctrine.contains_operator_processor.function')
            );
        }
    }
}
