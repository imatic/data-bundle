=============
Query objects
=============

Query objects use 2 main interfaces

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\QueryObjectInterface </Data/Query/QueryObjectInterface.php>`_

  - used to mark an object as query object

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\QueryExecutorInterface </Data/Query/QueryExecutorInterface.php>`_

  - used to retrieve result from given query object (data the query object describes how to access or affected number of rows by the executed query object)
  - default implementation is `QueryExecutor <query_executor_h_>`_

Querying data
-------------

First we need to create query object. Query object is object implementing
``Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface``. We won't implement this interface directly though.
Instead, we will implement interface of some driver.

Query objects can implement additional interfaces to affect how result will look like

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\ScalarResultQueryObjectInterface </Data/Query/ScalarResultQueryObjectInterface.php>`_

  - used to tell that a query object returns scalar result when executed
  - result of executing query by `query executor <query_executor_h_>`_ will be scalar result

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\SingleResultQueryObjectInterface </Data/Query/SingleResultQueryObjectInterface.php>`_

  - used to tell that a query object returns single row/object
  - result of executing query by `query executor <query_executor_h_>`_ will be

    - single row
    - ``null`` in case query returned no value
    - ``Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException`` in case query returned more rows

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\SingleScalarResultQueryObjectInterface </Data/Query/SingleScalarResultQueryObjectInterface.php>`_

  - used to tell that a query object returns single scalar result when executed
  - result of executing query by `query executor <query_executor_h_>`_ will be

    - single scalar value
    - ``Imatic\Bundle\DataBundle\Data\Query\NoResultException`` in case query returned no rows
    - ``Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException`` in case query returned more rows or columns


DoctrineDBAL driver
^^^^^^^^^^^^^^^^^^^

- `Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\QueryObjectInterface </Data/Driver/DoctrineDBAL/QueryObjectInterface.php>`_

  - uses `doctrine dbal <http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/introduction.html#introduction>`_ to query data
  - contains method ``build`` returning query builder which has all required info to execute the query

.. _basic_orm_example:

Example of query object for retrieving active users
"""""""""""""""""""""""""""""""""""""""""""""""""""

First we create our query object

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

   class ActiveUsersQuery implements QueryObjectInterface
   {
       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->select('u.*')
               ->from('user', 'u')
               ->where('u.active = :active')
               ->setParameter('active', true);
       }
   }

Then we can execute it using `query executor <query_executor_h_>`_

DoctrineORM driver
^^^^^^^^^^^^^^^^^^

- `Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\QueryObjectInterface </Data/Driver/DoctrineORM/QueryObjectInterface.php>`_

  - uses `doctrine orm <http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/getting-started.html#what-is-doctrine>`_ to query data
  - contains method ``build`` returning query builder which has all required info to execute the query

Example of query object for retrieving active users
"""""""""""""""""""""""""""""""""""""""""""""""""""

.. sourcecode:: php

   <?php

   use Doctrine\ORM\EntityManager;
   use Doctrine\ORM\QueryBuilder;
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;

   class ActiveUsersQuery implements QueryObjectInterface
   {
       public function build(EntityManager $em): QueryBuilder
       {
           return $em->getRepository(User::class)->createQueryBuilder('u')
               ->select('u')
               ->where('u.active = :active')
               ->setParameter('active', true);
       }
   }

Then we can execute it using `query executor <query_executor_h_>`_

Updating data
-------------

- query objects can be also used for updating/deleting data. Not just selecting them.

Example of deleting user with username ``eva`` using query objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First we need to create query object that will delete users by username

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

   class DeleteUserByUsernameQuery implements QueryObjectInterface
   {

       private $username;

       public function __construct($username)
       {
           $this->username = $username;
       }

       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->delete('user', 'u')
               ->where('u.username = :username')
               ->setParameter('username', $this->username);
       }
   }

Then we can execute it using `query executor <query_executor_h_>`_

.. sourcecode:: php

    <?php

    $queryExecutor = $container->get('Imatic\Bundle\DataBundle\Data\Query\QueryExecutor');

    $queryExecutor->execute(new DeleteUserByUsernameQuery('eva'));

.. _query_executor_h:

`QueryExecutor </Data/Query/QueryExecutor.php>`_
------------------------------------------------

- query executor is service which is able to execute given query object
- it has 3 methods

  - ``execute``

    - used to execute given query object and retrieve result

  - ``count``

    - used to find out how many records there will be if given query object is executed without pagination

  - ``executeAndCount``

    - combination of previous 2 (returns results and number of all results without pagination)

- all of the methods accept 2nd optional argument ``$displayCriteria`` which specifies filtering, sorting and pagination (more on that later)

Example of using query executor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   $queryExecutor = $container->get('Imatic\Bundle\DataBundle\Data\Query\QueryExecutor');

   $allActiveUsers = $queryExecutor->execute(new ActiveUsersQuery());
   $totalNumberOfActiveUsers = $queryExecutor->count(new ActiveUsersQuery());

Using multiple storage connections
----------------------------------

- in case application is using multiple storage connections (e.g.
  `doctrine connectinos <http://docs.doctrine-project.org/projects/doctrine1/en/latest/en/manual/connections.html#connections>`__
  in case of doctrine), connection can be specified by implementing ``ConnectionQueryObjectInterface`` and returning
  name of the connection in ``getConnectionName`` method.

Example of getting configuration via ``config`` connection
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Doctrine\DBAL\Connection;
   use Doctrine\DBAL\Query\QueryBuilder
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
   use Imatic\Bundle\DataBundle\Data\Query\ConnectionQueryObjectInterface;

   class UserConfigQuery implements QueryObjectInterface, ConnectionQueryObjectInterface
   {
       private $userId;

       public function __construct($userId)
       {
           $this->userId = $userId;
       }

       public function build(Connection $connection): QueryBuilder
       {
           return $connection->createQueryBuilder()
               ->select('c.*')
               ->from('user_config', 'c')
               ->where('c.user_id = :user_id')
               ->setParameter('user_id', $this->userId);
       }

       public function getConnectionName(): string
       {
           return 'config';
       }
   }

Making query object selectable
------------------------------

- marking query object selectable allows us to select required rows by some unique value (usually primary key)
- it can be made selectable by implementing ``SelectableQueryObjectInterface``

  - it has 1 method ``getIdentifierFilterKey`` which returns name of the filter we want to select rows by
  - it's used by record iterator typically used by commands to iterate over result with use of pagination

Executing query objects from console
------------------------------------

- query objects can be executed from console using ``imatic:data:query-object-query`` console command

Example of executing query object returning list of users
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: shell

   ./bin/console imatic:data:query-object-query 'App\Query\UserListQuery'

Example of executing query object returning single user with id passed via query object constructor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: shell

   ./bin/console imatic:data:query-object-query 'App\Query\UserQuery' --args 1

