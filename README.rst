.. image:: https://secure.travis-ci.org/imatic/data-bundle.png?branch=master
   :alt: Build Status
   :target: http://travis-ci.org/imatic/data-bundle
|
.. image:: https://img.shields.io/badge/License-MIT-yellow.svg
   :alt: License: MIT
   :target: LICENSE

ImaticDataBundle
================

This `bundle <https://symfony.com/doc/current/bundles.html>`_ makes it easy to work with data.

Main goals of the bundle
------------------------

- Accessing data in uniform way (no matter where they are stored) and possibility to have filtering, sorting, paging
  capabilities with small effort.
- Executing arbitrary operations (activating users, making orders in eshop...) in uniform way (no matter if the
  operation was executed by user from a browser, via some message queues, console or something else).

Accessing data in uniform way
-----------------------------

This bundle uses query objects to retrieve/store data from arbitrary storage. Currently, we have drivers for
`doctrine dbal <http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/introduction.html#introduction>`_
and `doctrine orm <http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/getting-started.html#what-is-doctrine>`_.
Other drivers can be relatively easily implemented.

All query objects have to implement ``QueryObjectInterface`` of specific driver in order to query data.

Example of querying active users using doctrine orm driver
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First we need to create query of active users.

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

Now we can execute the query using `query executor <Data/Driver/DoctrineORM/QueryExecutor.php>`_.

.. sourcecode:: php

   <?php

   $queryExecutor = $container->get('Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor');
   /** @var User[] */
   $activeUsers = $queryExecutor->execute(new ActiveUsersQuery());

Variable ``$activeUsers`` now contains objects of active users.


To learn more about query objects (how to do filtering, sorting, pagination, etc.) see
`query object documentation <Resources/doc/AccessingData/QueryObjects.rst>`_.

Executing operations
--------------------

This bundle uses commands to execute operations. Command instance is passed to a command executor, which calls command
handler to do the actual work.

Example of exporting all active users using command
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First we need to create command handler, which will export all active users in passed format (note that used class
``UserExporter`` does not exist and its responsibility is to export passed users in given format).

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
   use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;

   ExportActiveUsersHandler implements HandlerInterface
   {
       private $userExporter;
       private $queryExecutor;

       public __construct(UserExporter $userExporter, QueryExecutor $queryExecutor)
       {
           $this->userExporter = $userExporter;
           $this->queryExecutor = $queryExecutor;
       }

       public function handle(CommandInterface $command)
       {
           $exportFormat = $command->getParameter('format');
           $activeUsers = $this->queryExecutor->execute(new ActiveUsersQuery());
           $this->userExporter->export($activeUsers, $exportFormat);
       }
   }

Then we need to register the handler in the container.

.. sourcecode:: yaml

   services:
       ExportActiveUsersHandler:
           arguments:
               - '@app.user_exporter'
               - '@Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor'
           tags:
               - { name: 'imatic_data.handler' }

Then we can run the command via `command executor <Data/Command/CommandExecutor.php>`_. First argument of the command
is handler alias (specified when registering handler in the container), second argument is optional and specifies
options passed to the handler).

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $commandExecutor = $container->get('Imatic\Bundle\DataBundle\Data\Command\CommandExecutor');
   $commandExecutor->execute(new Command('export_active_users', ['format' => 'json']));


To learn more about commands, see `command documentation <Resources/doc/Operations/Commands.rst>`_.

Further reading
---------------

Visit our `documentation <Resources/doc/README.rst>`_ to learn about all features of this bundle.

