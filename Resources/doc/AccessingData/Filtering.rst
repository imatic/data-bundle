=========
Filtering
=========

In order to be able to filter results, we have to implement additional interface.

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\FilterableQueryObjectInterface </Data/Query/DisplayCriteria/FilterableQueryObjectInterface.php>`_

  - it has 1 method

    - ``getFilterMap`` - used to describe fields that can be used for filtering

.. _filtering_orm_example:

Let's create query object of active users with possiblity of filtering them using ``username`` and ``age`` columns
------------------------------------------------------------------------------------------------------------------

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;

   class ActiveUsersQuery implements QueryObjectInterface, FilterableQueryObjectInterface
   {
       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->select('u.*')
               ->from('user', 'u')
               ->where('u.active = :active')
               ->setParameter('active', true);
       }

       public function getFilterMap(): array
       {
           return [
               'username' => 'u.username',
               'age' => 'u.age',
           ];
       }
   }

The main method in this example is

- ``getFilterMap``

  - Specifies 2 named filters (fields we can filter by) ``username`` and ``age`` and tells us which fields should be
    used for each filter (``u.username`` for ``username`` filter and ``u.age`` for ``age`` filter)


`Filter </Data/Query/DisplayCriteria/Filter.php>`_
--------------------------------------------------

- used for specifying filtering
- it has property ``$filterRules`` which you can fill by array of `FilterRules <filter_rules_h_>`_ to filter by.
  You can do that either by using ``add`` method or extending the main filter and use the ``add`` method inside of
  overridden ``configure`` method.
- See `filter rules <filter_rules_h_>`_ to find out more about various types of filters.

.. _filter_usage_example:

Example of filtering object created in `filtering example <filtering_orm_example_>`_ by ``username``
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;

   $usernameRule = (new FilterRule\TextRule('username'))
       ->setValue('john')
       ->setOperator(FilterOperatorMap::OPERATOR_CONTAINS);

   $filter = (new Filter())
       ->add($usernameRule);

   $queryExecutor = $container->get('imatic_data.query_executor');

   $usersHavingJohnInUsername = $queryExecutor->execute(
       new ActiveUsersQuery(),
       new DisplayCriteria(
           new Pager(),
           new Sorter(),
           $filter
       )
   );

.. _filter_rules_h:

`FilterRule </Data/Query/DisplayCriteria/FilterRule.php>`_
----------------------------------------------------------

- used for specifying single rule for filtering
- it serves as base class for specific rule types
- it has 2 arguments

  - ``name`` - name of the filter specified in query object
  - ``options`` - additional options

- it has additional following interesting fields

  - ``value`` - value of the filter
  - ``operator``

    - operator we use for filtering (equals, contains, between, ...)
    - you can see predefined values in constants of `FilterOperatorMap </Data/Query/DisplayCriteria/FilterOperatorMap.php>`_ class

  - ``operators`` - array of allowed operators for the filter rule
  - ``formType`` - symfony form type used for rendering the form for user
  - ``formOptions`` - options for the form type above
  - ``type`` - type of the value (string, date, ...) - driver specific

there is `several predefined </Data/Query/DisplayCriteria/Filter/>`_ filter rules

`AjaxEntityChoiceRule </Data/Query/DisplayCriteria/Filter/AjaxEntityChoiceRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter results by their relations
- has 2 additional arguments

  - ``class``

    - class of the object we want to select

  - ``route``

    - route used to search through records

      - search string is passed in ``search`` filter
      - response is json

        - array of objects with keys

          - ``id`` - id of the object
          - ``text`` - text representation of the object

`ArrayRule </Data/Query/DisplayCriteria/Filter/ArrayRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter results by list of allowed values

Example
"""""""

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;

   $specificUsersRule = (new FilterRule\ArrayRule('username'))
       ->setValue(['john', 'eva']))

When we use the rule above, we get only users having ``username`` value ``john`` or ``eva`` when used with our
`query object <filtering_orm_example_>`_

`BooleanRule </Data/Query/DisplayCriteria/Filter/BooleanRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter results by boolean field

`ChoiceRule </Data/Query/DisplayCriteria/Filter/ChoiceRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. _date_range_rule_h:

`DateRangeRule </Data/Query/DisplayCriteria/Filter/DateRangeRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter values by date range
- value is associative array with keys ``start``, ``end``. Values for the fields can be either ``DateTime`` object or ``null``.

`DateTimeRangeRule </Data/Query/DisplayCriteria/Filter/DateTimeRangeRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- same as `DateRangeRule <date_range_rule_h_>`_ except it takes time into account

`TimeRangeRule </Data/Query/DisplayCriteria/Filter/TimeRangeRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- same as `DateRangeRule <date_range_rule_h_>`_ except it filters by time instead of date


`NumberRangeRule </Data/Query/DisplayCriteria/Filter/NumberRangeRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter values by numeric range
- value is associative array with keys ``start``, ``end``. Values for the fields can be either numeric value or ``null``.

`NumberRule </Data/Query/DisplayCriteria/Filter/NumberRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter results by numeric field

`TextRule </Data/Query/DisplayCriteria/Filter/TextRule.php>`_
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- used to filter results by string fields

`FilterFactory </Data/Query/DisplayCriteria/FilterFactory.php>`_
----------------------------------------------------------------

- used to create filters
- instead of building filter before executing query in our `filter example <filter_usage_example_>`_ we could create
  class for the filter like below

  .. sourcecode:: php

     <?php

     use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
     use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;
     use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

     class UserFilter extends Filter
     {
         protected function configure()
         {
              $usernameRule = (new FilterRule\TextRule('username'))
                  ->setValue('john')
                  ->setOperator(FilterOperatorMap::OPERATOR_CONTAINS);

              $this->add($usernameRule);
         }
     }

  - such class can be then tagged with some alias

    .. sourcecode:: yaml

       services:
           Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter:
               tags:
                   - { name: imatic_data.filter, alias: user_filter }

  - and then used either directly or via filter factory by using the alias

    .. sourcecode:: php

       <?php

       // creating user filter directly
       $userFilterDirectly = new UserFilter();

       // retrieving user filter via factory using the alias
       $filterFactory = $container->get('imatic_data.filter_factory');
       $userFilterViaFactory = $filterFactory->create('user_filter');

Custom filtering logic without implementing custom `FilterRule <filter_rules_h_>`__ and filter rule processor
-------------------------------------------------------------------------------------------------------------

- it's possible to pass callback as value in ``getFilterMap`` instead of column.
  Such callback then accepts 2 arguments: value returned by query object, existing filter rule specified for the filter.

Example of implementing custom filtering logic using callback
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\BooleanRule;
   use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;

   class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface
   {
       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->select('u.*')
               ->from('user', 'u')
               ->setParameter('active', true);
       }

       public function getFilterMap(): array
       {
           return [
               'activeEmployee' => function (QueryBuilder $qb, BooleanRule $rule) {
                   if ($rule->getOperator() === FilterOperatorMap::OPERATOR_EQUAL) {
                       if ($rule->getValue() === BooleanRule::YES) {
                           $qb->andWhere($qb->expr()->andX(
                               'u.active = :active',
                               'u.type' = ':type'
                           ));
                           $qb->setparameter('active', true);
                           $qb->setparameter('type', 'employee');
                       } elseif ($rule->getValue() === BooleanRule::NO) {
                           $qb->andWhere($qb->expr()->orX(
                               'u.active != :active',
                               'u.type' != 'employee'
                           ));
                           $qb->setparameter('active', true);
                           $qb->setparameter('type', 'employee');
                       }
                   }
               },
           ];
       }
   }

