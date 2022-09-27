==============
ResultIterator
==============

Result iterator is used to iterate over large amounts of results coming from query objects using pagination. As it's
direct use is not very convenient, check ``ResultIteratorFactory`` documentation for specific driver on how to create it.
It implements ``Iterator`` and ``Countable`` interfaces, so it can be used in foreach.

Example of using iterator on arbitrary amount of users to send emails
---------------------------------------------------------------------

.. sourcecode:: php

   <?php

   $users = createUsersIterator();
   foreach ($users as $user) {
       sendWeeklyEmail($user);
   }

