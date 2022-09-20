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
