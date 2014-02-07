<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
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
        $this->addReference(static::ADAM_REF, $adam);
        $manager->persist($adam);

        $eva = new User();
        $eva->setName('Eva');
        $this->addReference(static::EVA_REF, $eva);
        $manager->persist($eva);

        $manager->flush();
    }
}
