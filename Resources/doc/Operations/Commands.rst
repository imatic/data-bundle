========
Commands
========

Commands use 3 main interfaces

- `Imatic\\Bundle\\DataBundle\\Data\\Command\\CommandInterface </Data/Command/CommandInterface.php>`_

  - used to describe arbitrary operation
  - default implementation is `Command <command_h_>`_

- `Imatic\\Bundle\\DataBundle\\Data\\Command\\CommandExecutorInterface </Data/Command/CommandExecutorInterface.php>`_

  - used to execute commands
  - default implementation is `CommandExecutor <command_executor_h_>`_

- `Imatic\\Bundle\\DataBundle\\Data\\Command\\HandlerInterface </Data/Command/HandlerInterface.php>`_

  - used to handle commands (do the work)

.. _command_h:

`Command </Data/Command/Command.php>`_
--------------------------------------

- accepts 2 arguments

  - ``$handlerName`` - alias of the command handler service
  - ``$parameters`` - parameters used by command handler

Command is passed to the `command executor <command_executor_h_>`_ which executes `command handler <handler_>`_ based
on ``$handlerName`` argument


Example of command removing recording files older than one month
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $removeObsoleteRecordingsCommand = new Command(
       'cleanup_obsolete_files',
       [
           'fileType' => 'recordings',
           'olderThan' => new \DateTime('-1 month'),
       ]
    );

Handler
-------

- Has 1 method ``handle``, which accepts `command <command_h_>`_, processes it and returns a result.
- Needs to be registered in container in order to be called by `command executor <CommandExecutor_>`_

  - service needs to be tagged with tag ``imatic_data.handler`` having ``alias`` attribute

- In case handler needs a `command executor <command_executor_h_>`_ to be able to execute other commands,
  it can implement ``Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface`` to avoid circular reference
  exception in DI. It can also optionally use ``Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait`` trait.
- Result can be one of

  - ``void`` - command was handled successfully
  - ``boolean``

    - if ``true`` - command was handled successfully
    - if ``false`` - there was error during processing of the command

  - ``Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface``

    - See it's default implementation `CommandResult <command_result_h_>`_ for more details.


Example of handler removing specified type of files older than given period
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
   use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;

   ExportActiveUsersHandler implements HandlerInterface
   {
       public function handle(CommandInterface $command)
       {
           $fileType = $command->getParameter('fileType');
           $olderThan = $command->getParameter('olderThan');

           $oldRecordingFiles = findFilesByTypeOlderThan($fileType, $olderThan);
           foreach ($oldRecordingFiles as $file) {
               removeFile($file);
           }
       }
   }

.. sourcecode:: yaml

   services:
       ExportActiveUsersHandler:
           tags:
               - { name: 'imatic_data.handler', alias: 'cleanup_obsolete_files' }

.. _command_result_h:

`CommandResult </Data/Command/CommandResult.php>`_
--------------------------------------------------

- An instance can be optionally returned from `handler's <Handler_>`_ ``handle`` method.
- accepts 3 arguments

  - ``$success`` - boolean if handler processed command successfully
  - ``$messages`` - array of messages (messages can be shown to user, logged somewhere...)
  - ``Exception $exception`` - exception thrown when executing handler

- Implements 2 static factory methods ``success`` and ``error`` to conveniently create successful or unsuccessful
  result.
- In addition to parameters above, you can use method ``set`` to set additional data of the result (number of removed
  files, names of removed files, etc.). Data set using ``set`` are meant for some additional processing and can be
  retrieved by calling ``get`` on the result object.

Example of creating successful result
-------------------------------------

.. sourcecode:: php

   <?php

   $successResult = CommandResult::success('10 obsolete recording files were removed.');

Example of creating unsuccessful result
---------------------------------------

.. sourcecode:: php

   <?php

   $errorResult = CommandResult::error('Error happened. Please contact system administrator.');

.. _command_executor_h:

`CommandExecutor </Data/Command/CommandExecutor.php>`_
------------------------------------------------------

- Has 1 method ``execute`` which executes given command and returns result.
- Contains information about executed `command <command_h_>`_
- Returns `command result <command_result_h_>`_

.. sourcecode:: php

   <?php

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($removeObsoleteRecordingsCommand);

Preimplemented handlers
-----------------------

This bundle comes with several preimplemented handlers so that you don't have to implement command handlers for common
operations.

Doctrine DBAL handlers
^^^^^^^^^^^^^^^^^^^^^^

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Command\\CreateHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to create new rows in db table.
- Handler assumes that name of the column containing the key is ``id`` (if not explicitly passed, it's auto generated).
- Alias: ``imatic_data.doctrine_dbal.generic_create``
- Parameters:

  - ``table`` - name of the table we want to insert data into
  - ``data`` - data we want to insert into table. It's associative array where keys are column names and values are the
    actual data for the columns.

- Result:

  - ``result`` - contains id of the record

Example of inserting new user and echoing it's id
*************************************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $createUserCommand = new Command(
       'imatic_data.doctrine_dbal.generic_create',
       [
           'table' => 'user',
           'data' => [
               'email' => 'newuser@example.com',
               'user' => 'newuser',
           ],
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($createUserCommand);

   if ($result->isSuccessful()) {
       echo sprintf('Id of the inserted user: %d', $result->get('result'));
   } else {
       echo 'Error happened during executing the command.';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Command\\EditHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to update existing rows in db table.
- Alias: ``imatic_data.doctrine_dbal.generic_edit``
- Parameters:

  - ``id`` - id of the row we want to update. It's associative array where keys are column names and values are their
    values.
  - ``table`` - name of the table we want to update data in
  - ``data`` - data we want to update in table. It's associative array where keys are column names and values are the
    actual data for the columns.

- Result:

  - this handler doesn't return any result

Example of updating existing user with id equal to 1
****************************************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $updateUserCommand = new Command(
       'imatic_data.doctrine_dbal.generic_edit',
       [
           'id' => ['id' => 1],
           'table' => 'user',
           'data' => [
               'email' => 'updatedemail@example.com',
           ],
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($updateUserCommand);

   if ($result->isSuccessful()) {
       echo 'Email was successfully updated';
   } else {
       echo 'Error happened during updating of the email';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Command\\CreateOrEditHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to create new row in case one doesn't already exist (based on specified criteria) or edit existing one.
- Handler assumes that name of the column with primary key is ``id``.
- Alias: ``imatic_data.doctrine_dbal.generic_create_or_edit``
- Parameters:

  - ``columnValues`` - columns used to search existing record
  - ``table`` - table to search/update/insert records into
  - ``data`` - data to update in the new or existing row

- Result:

  - based on if data were created or updated, result is same as the one for generic create and update handlers

Example of creating or updating user with given email address
*************************************************************

- In the end we want to have user in our database with following columns

  - ``email`` - user@example.com
  - ``username`` - user

- In case, user with given email doesn't exist, we want to create him
- In case, user with given email does exist, we want his ``username`` to be ``user``

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $createOrUpdateUserCommand = new Command(
       'imatic_data.doctrine_dbal.generic_create_or_edit',
       [
           'columnValues' => [
               'email' => 'user@example.com',
            ],
           'table' => 'user',
           'data' => [
               'email' => 'user@example.com',
               'username' => 'user',
           ],
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($createOrUpdateUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully updated';
   } else {
       echo 'Error happened during updating of the user';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Command\\DeleteHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to delete row from db
- Alias: ``imatic_data.doctrine_dbal.generic_delete``
- Parameters:

  - ``id`` - id of the row we want to delete. It's associative array where keys are column names and values are their
    values.
  - ``table`` - name of the table we want to delete the row in

- Result:

  - this handler doesn't return any result

Example of deleting user with id 3
**********************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $deleteUserCommand = new Command(
       'imatic_data.doctrine_dbal.generic_delete',
       [
           'id' => ['id' => 3],
           'table' => 'user',
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($deleteUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully deleted';
   } else {
       echo 'Error happened during deleting of the user';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Command\\SoftDeleteHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to mark row in table as deleted.
- Handler assumes that:

  - column in which primary key is stored is named ``id``
  - table has column ``deleted_at`` which stores time at which row was marked as deleted

- Alias: ``imatic_data.doctrine_dbal.generic_soft_delete``
- Parameters:

  - ``id`` - id of the row we want to mark as deleted
  - ``table`` - table the row is in

- Result:

  - this handler doesn't return any result

Example of marking user with id 4 as deleted
********************************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $softDeleteUserCommand = new Command(
       'imatic_data.doctrine_dbal.generic_soft_delete',
       [
           'id' => ['id' => 4],
           'table' => 'user',
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($softDeleteUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully deleted';
   } else {
       echo 'Error happened during deleting of the user';
   }

Doctrine ORM handlers
^^^^^^^^^^^^^^^^^^^^^

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\Command\\CreateHandler
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to store new object in db.
- Alias: ``imatic_data.generic_create``
- Parameters:

  - ``class`` - class of the object we want to store into db
  - ``data`` - object of the class we want to store into db

- Result:

  - this handler doesn't return any result

Example of storing new user in db
*********************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $newUser = new User();
   $newUser->setEmail('new@example.com');
   $newUser->setUsername('newuser');

   $createUserCommand = new Command(
       'imatic_data.generic_create',
       [
           'class' => User::class,
           'data' => $newUser,
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($createUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully created';
   } else {
       echo 'Error happened during creating of the user';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\Command\\EditHandler
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to update db with edited data.
- Alias: ``imatic_data.generic_edit``
- Parameters:

  - ``class`` - class of the object we want to store into db
  - ``data`` - object of the class we want to store into db

- Result:

  - this handler doesn't return any result

Example of updating db with updated user
****************************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $updatedUser = findUserById(3);
   $updatedUser->setUsername('updatedusername');

   $updateUserCommand = new Command(
       'imatic_data.generic_edit',
       [
           'class' => User::class,
           'data' => $updatedUser,
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($updateUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully updated';
   } else {
       echo 'Error happened during updating of the user';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\Command\\DeleteHandler
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to delete existing object from db.
- At least one of ``data`` and ``query_object`` parameters have to be specified.
- Alias: ``imatic_data.generic_delete``
- Parameters:

  - ``class`` - class of the object we want to store into db
  - ``data`` - object of the class we want to remove from db
  - ``query_object`` - query object returning the object of the class

- Result:

  - this handler doesn't return any result

Example of deleting user
************************

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;


   $user = findUserById(5);

   $deleteUserCommand = new Command(
       'imatic_data.generic_delete',
       [
           'class' => User::class,
           'data' => $user,
       ]
   );

   $commandExecutor = $container->get('imatic_data.command_executor');
   $result = $commandExecutor->execute($deleteUserCommand);

   if ($result->isSuccessful()) {
       echo 'User was successfully deleted';
   } else {
       echo 'Error happened during deleting of the user';
   }

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\Command\\BatchHandler
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

- Used to execute given command for each object returned by executing query builder.
- Arguments:

  - ``RecordIterator``

    - service: ``imatic_data.driver.doctrine_orm.record_iterator`` (used to iterate through records with use of
      pagination)

  - ``$commandName``

    - alias of the command to execute for each record

  - ``$commandParameters``

    - parameters for the command

- Parameters:

  - ``batch_query``

    - query object which will be executed by the handler. Results will be passed into the command one by one.

  - ``batch_command_parameters`` (optional)

    - additional parameters for the command (parameters specified already in ``$commandParameters`` argument will be
      replaced by these). ``data`` parameter containing current object is first added to the list of parameters.

  - ``batch_command_parameters_callback`` (optional)

    - callback taking current parameters as argument and returning final array of parameters passed to the command

Example of deleting all inactive users
**************************************

- We already have command for deleting objects ``imatic_data.generic_delete``. That command removes only single object
  though.

First we register ``BatchHandler`` which will execute ``imatic_data.generic_delete`` command for each object returned
by a query object.

.. sourcecode:: yaml

   app.delete_inactive_users:
       class: Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\BatchHandler
       arguments:
           - '@imatic_data.driver.doctrine_orm.record_iterator'
           - '@imatic_data.generic_delete'
           - { class: User }
       tags:
           - { name: 'imatic.data_handler', alias: 'delete_inactive_users' }

Then we can execute the command. As batch command passes the user object to the child command in ``data`` parameter,
but our delete handler expects the user object in ``object`` parameter, we have to convert parameters using
``batch_command_parameters_callback``.

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\Command;

   $commandExecutor = $container->get('imatic_data.command_executor');
   $commandExecutor->execute(new Command(
       'delete_inactive_users',
       [
           'batch_query' => new InactiveUsersQuery(),
           'batch_command_parameters_callback' => function (array $commandParameters) {
               $commandParameters['object'] = $commandParameters['data'];

               return $commandParameters;
           }
       ]
   ));


