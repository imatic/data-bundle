==================
DoctrineDBAL utils
==================


`Schema </Data/Driver/DoctrineDBAL/Schema/Schema.php>`_
-------------------------------------------------------

- Helper to make it easier to work with doctrine DBAL.
- Automatic escaping of column names and table names (so it's possible to use reserved words)
- Uses correct type for each column, so DateTime is automatically converted to database value.

getQueryData
^^^^^^^^^^^^

- Method preparing data for queries.

  - 2 arguments:

    - ``$table`` - table we want to work with
    - ``$data`` - data we want to insert/update...

.. sourcecode:: php

   <?php

   $schema = $container->get('imatic_data.driver.doctrine_dbal.schema');

   $queryData = $schema->getQueryData('user', [
       'name' => 'John Doe',
       'score' => 20,
   ]);

   $this->connection->insert($queryData->getTable(), $queryData->getData(), $queryData->getTypes());

getColumnTypes
^^^^^^^^^^^^^^

- Method returning column types for each column in given table. Note that you can overwrite column types using ``imatic_data.column_types`` configuration. It can be useful if you're using database from which doctrine cannot guess types properly because database doesn't support comments on columns (like `sqlite <https://www.sqlite.org/>`_).

  - 1 argument

    - ``$table`` - table to return column types for

.. sourcecode:: php

   <?php

   $schema = $container->get('imatic_data.driver.doctrine_dbal.schema');

   $schema->getColumnTypes('user');

getNextIdValue
^^^^^^^^^^^^^^

- Generates next id for given table. Note that this works only if your database is using sequences (like `postgresql <https://www.postgresql.org/>`_).

  - 1 argument

    - ``$tableName`` - table name to generate id for

.. sourcecode:: php

   <?php

   $schema = $container->get('imatic_data.driver.doctrine_dbal.schema');

   $schema->getNextIdValue('user');

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

