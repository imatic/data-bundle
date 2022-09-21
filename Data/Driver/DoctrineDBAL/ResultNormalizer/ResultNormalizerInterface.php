<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Result;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

interface ResultNormalizerInterface
{
    public function normalize(QueryObjectInterface $queryObject, Result $result): array;
}
