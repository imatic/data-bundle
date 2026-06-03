<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\Order;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class LoadOrderData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $orders = [
            LoadUserData::ADAM_REF => 3,
            LoadUserData::EVA_REF => 7,
        ];

        foreach ($orders as $userRef => $orderCount) {
            for ($i = 0; $i < $orderCount; ++$i) {
                $manager->persist(new Order($this->getReference($userRef, User::class)));
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\DataFixtures\ORM\LoadUserData',
        ];
    }
}
