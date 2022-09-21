UPGRADE FROM 5.x to 6.0
=======================

QueryObject
-----------

* it is required that every`QueryObject` that selects data implements `Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface`

Before:
   ```php
   class UserListQuery implements QueryObjectInterface
   {
       public function build(EntityManager $em): QueryBuilder
       {
           // ...
       }
   }
   ```

After:
   ```php
   class UserListQuery implements QueryObjectInterface, ResultQueryObjectInterface
   {
       public function build(EntityManager $em): QueryBuilder
       {
           // ...
       }
   }
   ```

ResultNormalizer
----------------

The previous versions tried to automatically normalize DBAL query result e.g. convert database datetime value to PHP 
DateTime object. This is no longer available, use `Imatic\Bundle\DataBundle\Data\Query\NormalizeResultQueryObjectInterface`
to define normalized values and their [types](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html).

   ```php
   use use Doctrine\DBAL\Types\Types;

   class UserQuery implements QueryObjectInterface, NormalizeResultQueryObjectInterface
   {
       public function getNormalizerMap(): array
       {
           return [
               'birth_date' => Types::DATETIME_MUTABLE,
           ];
       }
   }
   ```
