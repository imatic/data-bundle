==============
RecordIterator
==============

.. _record_iterator_h:

`RecordIterator </Driver/DoctrineORM/Command/RecordIterator.php>`__
--------------------------------------------------------------------

- it allows easilly to iterate through selected records by users
- service: ``imatic_data.driver.doctrine_orm.record_iterator``
- methods

  - ``each``

    - iterates through records

  - ``eachIdentifier``

    - iterates through identifiers of records

- each method takes  `RecordIteratorArgs <record_iterator_args_h_>`__ as argument

.. _record_iterator_args_h:

`RecordIteratorArgs </Data/Driver/DoctrineORM/Command/RecordIteratorArgs.php>`__
---------------------------------------------------------------------------------

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

    - overwrites default limit per page

Example of handler deactivating all selected users selected by user
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

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

