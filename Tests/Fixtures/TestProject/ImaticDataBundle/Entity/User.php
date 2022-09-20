<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_user")
 *
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    private $activated = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $favoriteDay;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $favoriteTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $hairs;

    /**
     * @var Order[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     */
    private $orders;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
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

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate(\DateTime $birthDate = null)
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

    public function setFavoriteDay(\DateTime $favoriteDay = null)
    {
        $this->favoriteDay = $favoriteDay;
    }

    public function setFavoriteTime(\Datetime $favoriteTime = null)
    {
        $this->favoriteTime = $favoriteTime;
    }

    /**
     * @return string
     */
    public function getHairs()
    {
        return $this->hairs;
    }

    /**
     * @param string|null $hairs
     */
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
