=====================
ResultIteratorFactory
=====================

Factory creating instance of `ResultIterator <../AccessingData/ResultIterator.rst>`_.
Available as a service under ``imatic_data.driver.orm.result_iterator_factory`` alias.

It has 1 main method ``create`` which accepts following arguments

- ``QueryObjectInterface $queryObject``

  - Query object corresponding with results we want to iterate over.

- ``array $criteria``

  - Criteria for filtering/sorting/pagination.
  - Requires ``filter_type`` value to be alias of tagged ``Filter``.

- ``FilterInterface $filter``

  - Optional argument

Example of iterating through users with loading at most 50 users at the time
----------------------------------------------------------------------------

.. sourcecode:: php

   <?php

   $resultIteratorFactory = $container->get('imatic_data.driver.doctrine_orm.result_iterator_factory');
   $users = $resultIteratorFactory->create(
       new UserListQuery(),
       [
           'filter_type' => 'user_filter',
           'limit' => 50,
       ]
   );

   foreach ($users as $user) {
       sendWeeklyEmail($user);
   }

