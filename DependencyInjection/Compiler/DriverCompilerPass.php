<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Driver\DriverRepository;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $drivers = $container->findTaggedServiceIds('imatic_data.driver');
        $driverRepositoryDef = $container->findDefinition(DriverRepository::class);

        $ids = \array_keys($drivers);
        foreach ($ids as $id) {
            $driverRepositoryDef->addMethodCall('add', [
                new Reference($id),
            ]);
        }
    }
}
