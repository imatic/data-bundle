==============
ResultIterator
==============

Result iterator is used to iterate over big amount of results of query objects using pagination. As it's use is not very nice directly, check ``ResultIteratorFactory`` documentation for specific driver on how to create it. It implements ``Iterator`` and ``Countable`` interfaces, so you can use it in foreach.

Example of using iterator on arbitrary amount of users to send emails
---------------------------------------------------------------------

.. sourcecode:: php

   <?php

   $users = createUsersIterator();
   foreach ($users as $user) {
       sendWeeklyEmail($user);
   }

