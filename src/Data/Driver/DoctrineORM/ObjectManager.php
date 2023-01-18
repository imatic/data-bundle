<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\Persistence\ManagerRegistry;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;

class ObjectManager implements ObjectManagerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param string|null $name The object manager name (null for the default one).
     */
    public function flush(string $name = null): void
    {
        $this->registry->getManager($name)->flush();
    }

    /**
     * @param string|null $name The object manager name (null for the default one).
     */
    public function persist(object $object, string $name = null): void
    {
        $this->registry->getManager($name)->persist($object);
    }

    /**
     * @param string|null $name The object manager name (null for the default one).
     */
    public function remove(object $object, string $name = null): void
    {
        $this->registry->getManager($name)->remove($object);
    }
}
