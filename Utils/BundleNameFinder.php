<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Utils;

class BundleNameFinder
{
    /**
     * @var array
     */
    private $bundles;

    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function find($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        do {
            $classNamespace = $reflectionClass->getNamespaceName();
            foreach ($this->bundles as $bundleName => $bundleClass) {
                $bundleNamespace = \substr($bundleClass, 0, \strrpos($bundleClass, '\\'));
                if (0 === \strpos($classNamespace, $bundleNamespace)) {
                    return $bundleName;
                }
            }
            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass);

        return null;
    }
}
