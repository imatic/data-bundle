<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\ContainsOperatorProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainsOperatorProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('imatic_data.doctrine.contains_operator_processor.function')) {
            return;
        }

        $container->findDefinition(ContainsOperatorProcessor::class)
            ->addMethodCall(
                'setFunction',
                ['%imatic_data.doctrine.contains_operator_processor.function%']
            );
    }
}
