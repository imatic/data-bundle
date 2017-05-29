=====================
ResultIteratorFactory
=====================

Factory creating instance of `ResultIterator <../AccessingData/ResultIterator.rst>`_. It can be retrieved from the container using ``imatic_data.driver.doctrine_dbal.result_iterator_factory`` service.

It has 1 main method ``create`` which accepts following arguments

- ``QueryObjectInterface $queryObject``

  - query object of which results we want to iterate over

- ``array $criteria``

  - criteria for filtering/sorting/pagination
  - requires ``filter_type`` value to be alias of tagged ``Filter``

- ``FilterInterface $filter``

  - optional argument

Example of iterating through users with loading at most 50 users at once
------------------------------------------------------------------------

.. sourcecode:: php

   <?php

   $resultIteratorFactory = $container->get('imatic_data.driver.doctrine_dbal.result_iterator_factory');
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

