==================
DoctrineDBAL utils
==================


`Schema </Data/Driver/DoctrineDBAL/Schema/Schema.php>`_
-------------------------------------------------------

- helper to make it easier to work with doctrine dbal
- automatic escaping of names of colums and tables (so it's possible to use reserved words)
- uses correct type for each column, so DateTime is automatically converted to db value

.. sourcecode:: php

   <?php

   $queryData = $this->schema->getQueryData($table = 'user', $data = [
       'name' => 'John Doe',
       'score' => 20,
   ]);

   $this->connection->insert($queryData->getTable(), $queryData->getData(), $queryData->getTypes());

`Sql </Data/Driver/DoctrineDBAL/Sql/Sql.php>`_
----------------------------------------------

- helper allowing to create queries independent of concrete db

Sql::concat
^^^^^^^^^^^

- method allowing to concatenate colums independent of concrete db

  - 2 arguments

    - ``$args`` - list of columns to concatenate
    - ``$connection`` - doctrine dbal connection

.. sourcecode:: php

   <?php

   $query = sprintf(
       'SELECT  u.id AS id %s AS full_name FROM user',
       Sql::concat([
           'u.first_name',
           ' ',
           'u.last_name',
       ],
       $this->connection
    );

