=========
QueryUtil
=========

Utility class which helps with creation of queries.

It has following methods:

generateParameterName
---------------------

- Takes 1 optional argument ``prefix`` where you can specify prefix of generated parameter name.
- Each call returns unique name of parameter (assuming prefix doesn't end with number - in that case, conflict can occur)
- Can be used when generating parameter names in multiple places to make sure that there will be no conflicts.

