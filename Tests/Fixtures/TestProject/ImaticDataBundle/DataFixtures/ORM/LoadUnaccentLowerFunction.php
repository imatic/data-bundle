<?php

namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUnaccentLowerFunction implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->getConnection()->executeQuery(
            <<<'SQL'
CREATE OR REPLACE FUNCTION unaccent_lower(text)
  RETURNS text AS
$func$
SELECT lower(translate($1
     , '¹²³áàâãäåāăąÀÁÂÃÄÅĀĂĄÆćčç©ĆČÇĐÐèéêёëēĕėęěÈÊËЁĒĔĖĘĚ€ğĞıìíîïìĩīĭÌÍÎÏЇÌĨĪĬłŁńňñŃŇÑòóôõöōŏőøÒÓÔÕÖŌŎŐØŒř®ŘšşșßŠŞȘùúûüũūŭůÙÚÛÜŨŪŬŮýÿÝŸžżźŽŻŹ'
     , '123aaaaaaaaaaaaaaaaaaacccccccddeeeeeeeeeeeeeeeeeeeeggiiiiiiiiiiiiiiiiiillnnnnnnooooooooooooooooooorrrsssssssuuuuuuuuuuuuuuuuyyyyzzzzzz'
     ));
$func$ LANGUAGE sql IMMUTABLE;
SQL
        );
    }
}
