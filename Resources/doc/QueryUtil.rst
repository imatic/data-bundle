=========
QueryUtil
=========

Util class which helps with creation of queries.

It has following methods

generateParameterName
---------------------

- it takes 1 optional argument ``prefix`` where you can specify prefix of generated parameter name
- each call returns unique name of parameter (assuming prefix doesn't end with number - in which case conflict could occur)
- it can be used when generating parameter names in multiple places to make sure that we won't have conflicts

