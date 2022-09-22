<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

/**
 * In case query object implements this interface, it will be subjected to some
 * experimental optimizations.
 *
 * This feature is in early stage an is probably buggy in many cases.
 * Use with caution and report all found bugs.
 *
 * It is possible that this interface will be removed in a future and optimizations will be
 * turned on for all query objects.
 */
interface ExperimentalOptimizationQueryObjectInterface
{
}
