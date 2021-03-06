==============
RecordIterator
==============

.. _record_iterator_h:

`RecordIterator </Driver/DoctrineORM/Command/RecordIterator.php>`__
-------------------------------------------------------------------

- Allows easily to iterate over records selected by users.
- Service id: ``Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIterator``.
- Methods:

  - ``each``

    - Iterates over records.

  - ``eachIdentifier``

    - Iterates over identifiers of records.

- Each method takes  `RecordIteratorArgs <record_iterator_args_h_>`__ as argument

.. _record_iterator_args_h:

`RecordIteratorArgs </Data/Driver/DoctrineORM/Command/RecordIteratorArgs.php>`__
--------------------------------------------------------------------------------

- argument to the methods of `RecordIterator <record_iterator_h_>`__
- has 3 arguments

  - ``$command``

    - command of the handler

  - ``$queryObject``

    - query object user selected records from

  - ``$callback``

    - callable with 1 argument based on called method

      - ``each`` passes record
      - ``eachIdentifier`` passes identifier of the record

- has 1 method

  - ``setLimit``

    - Overwrites default limit per page.

Example of handler deactivating all user records selected by user
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. sourcecode:: php

   <?php

   use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
   use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
   use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\Recorditerator;
   use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIteratorArgs;

   DeactivateUsersHandler implements HandlerInterface
   {

       private $recordIterator;

       public function __construct(RecordIterator $recordIterator)
       {
           $this->recordIterator = $recordIterator;
       }

       public function handle(CommandInterface $command)
       {
           $recordIteratorArgs = new RecordIteratorArgs(
               $command,
               new UserListQuery(),
               function (User $user) {
                   deactivate($user);

                   return CommandResult::success();
               }
           );

           $this->recordIterator->execute($recordIteratorArgs);
       }
   }

