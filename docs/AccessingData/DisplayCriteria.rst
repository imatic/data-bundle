===============
DisplayCriteria
===============

Display criteria uses 1 main interface

- `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\DisplayCriteriaInterface </Data/Query/DisplayCriteria/DisplayCriteriaInterface.php>`_

  - used to alter query objects by

    - using pagination
    - applying filters
    - applying sorters

  - default implementation is `DisplayCriteria <display_criteria_h_>`_

.. _display_criteria_h:

`DisplayCriteria </Data/Query/DisplayCriteria.php>`__
-----------------------------------------------------

- display criteria is object which allows us to use filtering, sorting and pagination when using one of the 3 methods of `query executor <QueryObjects.rst>`_
- can be created either manually or by  `DisplayCriteriaFactory <display_criteria_factory_h_>`_ from request
- it has 3 arguments

  - ``pager``

    - used for pagination
    - object implementing `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\PagerInterface </Data/Query/DisplayCriteria/PagerInterface.php>`_

      - more info in `Pager <Pagination.rst>`_ documentation

  - ``sorter``

    - used for specifying sorting
    - object implementing `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\SorterInterface </Data/Query/DisplayCriteria/SorterInterface.php>`_

      - more info in `Sorter <Sorting.rst>`_ documentation

  - ``filter``

    - used for specifying filtering
    - object implementing `Imatic\\Bundle\\DataBundle\\Data\\Query\\DisplayCriteria\\FilterInterface </Data/Query/DisplayCriteria/FilterInterface.php>`_

      - more info in `Filter <Filtering.rst>`_ documentation

.. _display_criteria_factory_h:

`DisplayCriteriaFactory </Data/Query/DisplayCriteria/DisplayCriteriaFactory.php>`_
----------------------------------------------------------------------------------

- creates `DisplayCriteria <display_criteria_h_>`_ from request (or whatever else using `DisplayCriteriaReader <display_criteria_reader_h_>`_)
- service with ``Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory`` id
- it has 1 main method ``createCriteria`` with 2 arguments

  - ``options``

    - associative array

      - ``componentId`` - id to distinguish between several components on a page
      - ``pager``

        - optional array with optional keys (values are used only as default values in case request doesn't contain any)

          - ``page`` - current page
          - ``limit`` - maximum items per page

      - ``filter``

        - filter with filter rules describing fields - which and how can be filtered

      - ``sorter``

        - optional array of default sorters

  - ``persistent``

    - boolean if current filter/sorter/pager values should be persisted (so if user opens page next time without
      specifying any criteria, he will see the last used)

.. _display_criteria_reader_h:

`DisplayCriteriaReader </Data/Query/DisplayCriteria/Reader/DisplayCriteriaReader.php>`_
---------------------------------------------------------------------------------------

- reader used by `DisplayCriteriaFactory <display_criteria_factory_h_>`_ to create filter/pager/sorter from user input

- bundle ships with 2 main implementations

  - `RequestQueryReader </Data/Query/DisplayCriteria/Reader/RequestQueryReader.php>`_ (default one)

    - used to read data from request
    - data are stored in url

      - format

        - ``filter``

          - associative array

            - ``clearFilter``

              - optional boolean which causes all filter values to be cleared

            - ``defaultFilter``

              - optional boolean which causes setting all filter values to their defaults

            - other keys are programmer defined filters (associative arrays) where each item is indexed by filter name
              and it's value is associative array with following keys:

              - ``operator``

                - user selected operator of ``FilterRule``

              - ``value``

                - user selected value of the filter

        - ``sorter``

          - associative array with sorter as key and direction as a value

        - ``page``

          - current page (used for pagination)

        - ``limit``

          - maximum records per page

  - `ExtJsReader </Data/Query/DisplayCriteria/Reader/ExtJsReader.php>`_

    - used to read data from request in format `ExtJs <https://www.sencha.com/products/extjs/#overview>`_ uses by default

