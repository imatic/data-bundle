<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class User
{
    /**
     * @var int
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
     * @ORM\Column(type="boolean")
     */
    private $activated = true;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $hairs;

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
    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getHairs()
    {
        return $this->hairs;
    }

    /**
     * @param string $hairs
     */
    public function setHairs($hairs)
    {
        $this->hairs = $hairs;
    }
}
