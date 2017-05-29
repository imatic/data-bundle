=================================
Using commands with query objects
=================================

When using query objects in handlers, one must work with several command parameters and various services around to get actual list of records.

- ``selected``

  - array of record indentifiers selected by user (should be used in case ``selectedAll`` is ``false``)

- ``query``

  - display criteria parameters

- ``selectedAll``

  - boolean

    - if ``true`` - all records matching ``DisplayCriteria`` defined in ``query`` command parameter should be selected
    - if ``false`` - all records with identifier in command parameter ``selected`` should be selected

To make the work easier, each driver in this bundle has ``RecordIterator`` which abstracts you away from these details. See documentation for concrete driver on how to use ``RecordIterator``.

