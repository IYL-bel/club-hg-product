<?php
/**
 * Test task
 *
 * Gender entity
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test\UserBundle\Entity\Gender
 *
 * @ORM\Table(name="gender")
 * @ORM\Entity(repositoryClass="Test\UserBundle\Repository\GenderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Gender
{

    const GENDER__MALE    = 1;
    const GENDER__FEMALE  = 2;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $gender;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Users", mappedBy="gender")
     * @ORM\JoinColumn(name="id", referencedColumnName="gender_id")
     */
    protected $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $gender
     * @return \Test\UserBundle\Entity\Gender
     * @throws \InvalidArgumentException
     */
    public function setGender($gender)
    {
        if (!in_array($gender, array(self::GENDER__MALE, self::GENDER__FEMALE))) {
            throw new \InvalidArgumentException("Invalid gender");
        }
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string|null $nameType
     * @return string
     */
    public function getGenderString($nameType = 'user.gender.')
    {
        switch ($this->gender) {
            case self::GENDER__MALE:
                $string = $nameType . 'male';
                break;

            case self::GENDER__FEMALE:
                $string = $nameType . 'female';
                break;

            default:
                $string = null;
                break;
        }

        return $string;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $users
     * @return \Test\UserBundle\Entity\Gender
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

}
