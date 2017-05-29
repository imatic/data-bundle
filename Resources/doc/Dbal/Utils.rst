==================
DoctrineDBAL utils
==================


`Schema </Data/Driver/DoctrineDBAL/Schema/Schema.php>`_
-------------------------------------------------------

- Helper to make it easier to work with doctrine DBAL.
- Automatic escaping of column names and table names (so it's possible to use reserved words)
- Uses correct type for each column, so DateTime is automatically converted to database value.

.. sourcecode:: php

   <?php

   $queryData = $this->schema->getQueryData($table = 'user', $data = [
       'name' => 'John Doe',
       'score' => 20,
   ]);

   $this->connection->insert($queryData->getTable(), $queryData->getData(), $queryData->getTypes());

`Sql </Data/Driver/DoctrineDBAL/Sql/Sql.php>`_
----------------------------------------------

- Helper allowing to create queries independent on concrete database implementation.

Sql::concat
^^^^^^^^^^^

- Method allowing to concatenate values independent on database implementation.

  - 2 arguments

    - ``$args`` - list of columns to concatenate
    - ``$connection`` - doctrine DBAL connection

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

