=============
DQL functions
=============

This bundle ships with several dql functions. To register all of them, add following to your ``config.yml``

.. sourcecode:: yaml

    doctrine:
        orm:
            dql:
                string_functions:
                    ilike: Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST\ILike
                    not_ilike: Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST\NotILike
                    cast: Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST\Cast
                    overlaps: Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST\Overlaps
                    unaccent_lower: Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST\UnaccentLower

`ILike </Doctrine/Postgresql/Query/AST/ILike.php>`_
----------------------------------------------------

- case insensitive version of ``LIKE``

.. sourcecode:: text

   SELECT u
   FROM User u
   WHERE ILIKE(u.username, '%adam%')

`NotILike </Doctrine/Postgresql/Query/AST/NotILike.php>`_
---------------------------------------------------------

- case insensitive version of ``NOT LIKE``

.. sourcecode:: text

   SELECT u
   FROM User u
   WHERE NOT_ILIKE(u.username, '%adam%')


`Cast </Doctrine/Common/Query/AST/Cast.php>`_
---------------------------------------------

https://www.postgresql.org/docs/9.6/static/sql-createcast.html

`Overlaps </Doctrine/Common/Query/AST/Overlaps.php>`_
-----------------------------------------------------

https://www.postgresql.org/docs/9.6/static/functions-datetime.html

`UnaccentLower </Doctrine/Common/Query/AST/UnaccentLower.php>`_
---------------------------------------------------------------

- function to remove accent from given columns and make it lowercase
- this function will call db function called ``unaccent_lower`` by default (this can be overriden by calling ``UnaccentLower::setFunction``
- as ``unaccent_lower`` probably doesn't exist in your db, you have to create it using e.g. following query

  .. sourcecode:: sql

     CREATE OR REPLACE FUNCTION unaccent_lower(text)
       RETURNS text AS
     $func$
     SELECT lower(translate($1
          , '¹²³áàâãäåāăąÀÁÂÃÄÅĀĂĄÆćčç©ĆČÇĐÐèéêёëēĕėęěÈÊËЁĒĔĖĘĚ€ğĞıìíîïìĩīĭÌÍÎÏЇÌĨĪĬłŁńňñŃŇÑòóôõöōŏőøÒÓÔÕÖŌŎŐØŒř®ŘšşșßŠŞȘùúûüũūŭůÙÚÛÜŨŪŬŮýÿÝŸžżźŽŻŹ'
          , '123aaaaaaaaaaaaaaaaaaacccccccddeeeeeeeeeeeeeeeeeeeeggiiiiiiiiiiiiiiiiiillnnnnnnooooooooooooooooooorrrsssssssuuuuuuuuuuuuuuuuyyyyzzzzzz'
          ));
     $func$ LANGUAGE sql IMMUTABLE;

.. sourcecode:: text

   SELECT UNACCENT_LOWER(u.username)
   FROM User u

