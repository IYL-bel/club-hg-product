<?php
/**
 * Test task
 *
 * Users entity
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test\UserBundle\Entity\Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Test\UserBundle\Repository\UsersRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Users
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="birth_date")
     */
    protected $birthDate;

    /**
     * @var Gender
     *
     * @ORM\ManyToOne(targetEntity="Gender", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="gender_id", referencedColumnName="id", nullable=false)
     */
    protected $gender;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return \Test\UserBundle\Entity\Users
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $name
     * @return \Test\UserBundle\Entity\Users
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \DateTime $birthDate
     * @return \Test\UserBundle\Entity\Users
     */
    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \Test\UserBundle\Entity\Gender $gender
     */
    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return \Test\UserBundle\Entity\Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

}
