<?php

namespace Klac\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 * @package Klac\AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="Klac\AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 *
 * @UniqueEntity(
 *     fields={"email"}
 * )
 * @UniqueEntity(
 *     fields={"username"}
 * )
 */
class User extends BaseUser
{
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @Serializer\Groups({"brief_view", "view_user"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30, nullable=true)
     *
     * @Serializer\Groups({"brief_view", "view_user", "update"})
     */
    protected $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     *
     * @Serializer\Groups({"brief_view", "view_user", "update"})
     */
    protected $age;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Serializer\Groups({"brief_view", "view_user", "update"})
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     *
     * @Serializer\Groups({"brief_view", "view_user", "update"})
     */
    protected $sex;

    /**
     * @ORM\ManyToMany(targetEntity="Klac\AppBundle\Entity\Order", mappedBy="users")
     *
     * @Serializer\Groups({"view_user"})
     */
    protected $orders;

    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->orders = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     * @return User
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
    }
}