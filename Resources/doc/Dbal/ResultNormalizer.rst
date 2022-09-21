================
ResultNormalizer
================

In order to be able to normalize results, we have to implement additional interface with `QueryObject <../AccessingData/QueryObjects.rst>`_.

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\NormalizeResultQueryObjectInterface </Data/Query/NormalizeResultQueryObjectInterface.php>`_

  - it has 1 methods

    - ``getNormalizerMap`` - used to describe fields that can be normalized with their `type <https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html>`_

.. sourcecode:: php

   <?php

   use use Doctrine\DBAL\Types\Types;
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

   class UserQuery implements QueryObjectInterface, NormalizeResultQueryObjectInterface
   {
       // ...

       public function getNormalizerMap(): array
       {
           return [
               'birth_date' => Types::DATETIME_MUTABLE,
           ];
       }
   }

