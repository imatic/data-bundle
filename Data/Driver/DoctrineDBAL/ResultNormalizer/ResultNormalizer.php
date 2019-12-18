<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Driver\PDOStatement;

interface ResultNormalizer
{
    public function normalize(PDOStatement $statement);
}
