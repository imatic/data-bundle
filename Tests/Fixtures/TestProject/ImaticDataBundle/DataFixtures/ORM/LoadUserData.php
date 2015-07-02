<?php

namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class LoadUserData extends AbstractFixture
{
    const ADAM_REF = 'user-adam';
    const EVA_REF = 'eva-ref';

    public function load(ObjectManager $manager)
    {
        $adam = new User();
        $adam->setName('Adam');
        $adam->activate();
        $adam->setBirthDate(new \DateTime('1990-01-01'));
        $adam->setFavoriteDay(new \DateTime('2013-05-03'));
        $adam->setFavoriteTime(new \DateTime('5:32'));
        $adam->setHairs('short');
        $this->addReference(static::ADAM_REF, $adam);
        $manager->persist($adam);

        $eva = new User();
        $eva->setName('Eva');
        $eva->deactivate();
        $eva->setBirthDate(new \DateTime('1995-03-05'));
        $eva->setFavoriteDay(new \DateTime('1983-06-03'));
        $eva->setFavoriteTime(new \DateTime('12:00'));
        $eva->setHairs('long');
        $this->addReference(static::EVA_REF, $eva);
        $manager->persist($eva);

        $manager->flush();
    }
}
