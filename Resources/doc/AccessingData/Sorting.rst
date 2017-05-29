=======
Sorting
=======

In order to be able to choose by which field we want to sort the result, we have to implement additional interface.

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\SortableQueryObjectInterface </Data/Query/DisplayCriteria/SortableQueryObjectInterface.php>`_

  - it has 2 methods

    - ``getSorterMap`` - used to describe fields that can be used for sorting
    - ``getDefaultSort`` - specifies default fields to sort by

.. _sorting_orm_example:

Let's create query object of active users with possibility to sorting them by ``username`` and ``age`` columns
--------------------------------------------------------------------------------------------------------------

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

   class ActiveUsersQuery implements QueryObjectInterface, SortableQueryObjectInterface
   {
       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->select('u.*')
               ->from('user', 'u')
               ->where('u.active = :active')
               ->setParameter('active', true);
       }

       public function getSortedMap(): array
       {
           return [
               'username' => 'u.username',
               'age' => 'u.age',
           ];
       }

       public function getDefaultSort(): array
       {
           return [
               'username' => 'ASC',
           ];
       }
   }


As you can see we added 2 new methods

- ``getSorterMap``

  - it specifies 2 named sorterers (fields we can sort by) ``username`` and ``age`` and tells us which fields should be used for each sorter (``u.username`` for ``username`` sorter and ``u.age`` for ``age`` sorter)

- ``getDefaultSort``

  - it specifies how result will be sorted in case we don't specify which sorter to use (data are sorted in ascending order by ``username`` sorter in our case)



`Sorter </Data/Query/DisplayCriteria/Sorter.php>`_
--------------------------------------------------

- used for specifying sorting using `sorter rules <sorter_rule_h_>`_

.. _sorter_rule_h:

`SorterRule </Data/Query/DisplayCriteria/SorterRule.php>`_
----------------------------------------------------------

- used for specifying single rule for sorting
- it has 2 arguments

  - ``column``
  - ``direction`` - direction of sorting (``ASC`` - ascending, ``DESC`` - descending)

Example of sorting object created in `sorting example <sorting_orm_example_>`_ by ``age``
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;

   $queryExecutor = $container->get('imatic_data.query_executor');

   $sortedActiveUsers = $queryExecutor->execute(
       new ActiveUsersQuery(),
       new DisplayCriteria(
           new Pager(),
           new Sorter([
               new SorterRule('age', SorterRule::ASC),
           ]),
           new Filter()
       )
   );

