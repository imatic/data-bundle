<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'test_user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private $activated = true;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $birthDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $favoriteDay;

    #[ORM\Column(type: 'time', nullable: true)]
    private $favoriteTime;

    #[ORM\Column(type: 'string', nullable: true)]
    private $hairs;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private $orders;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function isActivated()
    {
        return $this->activated;
    }

    public function activate()
    {
        $this->activated = true;
    }

    public function deactivate()
    {
        $this->activated = false;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTime $birthDate = null)
    {
        $this->birthDate = $birthDate;
    }

    public function getFavoriteDay()
    {
        return $this->favoriteDay;
    }

    public function getFavoriteTime()
    {
        return $this->favoriteTime;
    }

    public function setFavoriteDay(?\DateTime $favoriteDay = null)
    {
        $this->favoriteDay = $favoriteDay;
    }

    public function setFavoriteTime(?\Datetime $favoriteTime = null)
    {
        $this->favoriteTime = $favoriteTime;
    }

    public function getHairs()
    {
        return $this->hairs;
    }

    public function setHairs($hairs)
    {
        $this->hairs = $hairs;
    }

    /**
     * @return Order[]|Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
