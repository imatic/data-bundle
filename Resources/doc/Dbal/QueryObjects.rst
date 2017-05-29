========================
Predefined query objects
========================

`DistinctQuery </Data/Driver/DoctrineDBAL/Query/DistinctQuery.php>`_
--------------------------------------------------------------------

- used to retrieve distinct values of a column in a table
- 2 arguments

  - ``$table``
  - ``$column``

`RecordIdQuery </Data/Driver/DoctrineDBAL/Query/RecordIdQuery.php>`_
--------------------------------------------------------------------

- used to retrieve ``id`` of row in a table which has specified column values
- query assumes that primary key column name is ``id``
- 2 arguments

  - ``$table``
  - ``$columnValues``

    - array of column values to search by

`SoftDeleteQuery </Data/Driver/DoctrineDBAL/Query/SoftDeleteQuery.php>`_
------------------------------------------------------------------------

- used to mark row as deleted
- query assumes that

  - primary key column name is ``id``
  - table has column ``deleted_at`` which stores time, at which row was marked as deleted

- 2 arguments

  - ``$table``
  - ``$id``

