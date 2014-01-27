<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use Doctrine\Common\Persistence\ObjectManager;

interface QueryObjectInterface
{
    /**
     * @param ObjectManager $om
     * @return mixed Instance of QueryBuilder, concrete type depends on used persistence backend
     */
    public function build(ObjectManager $om);
}
