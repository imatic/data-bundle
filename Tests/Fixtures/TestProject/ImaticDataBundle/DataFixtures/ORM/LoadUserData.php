<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $adam = new User();
        $adam->setName('Adam');
        $manager->persist($adam);

        $eva = new User();
        $eva->setName('Eva');
        $manager->persist($eva);

        $manager->flush();
    }
}
