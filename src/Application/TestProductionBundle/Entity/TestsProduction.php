<?php
/**
 * Club Hg-Product
 *
 * Test Production entity
 *
 * @package    ApplicationTestProductionBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\TestProductionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Application\TestProductionBundle\Entity\TestsProduction
 *
 * @ORM\Table(name="tests_production")
 * @ORM\Entity(repositoryClass="Application\TestProductionBundle\Repository\TestsProduction")
 * @ORM\HasLifecycleCallbacks
 */
class TestsProduction
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
     * @var \Application\UsersBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="testsProduction")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name_product", length=150, nullable=true)
     */
    protected $nameProduct;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="comment_user", nullable=true)
     */
    protected $commentUser;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $phone;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;


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
    public function getCommentUser()
    {
        return $this->commentUser;
    }

    /**
     * @param string $commentUser
     */
    public function setCommentUser($commentUser)
    {
        $this->commentUser = $commentUser;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getNameProduct()
    {
        return $this->nameProduct;
    }

    /**
     * @param string $nameProduct
     */
    public function setNameProduct($nameProduct)
    {
        $this->nameProduct = $nameProduct;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \Application\UsersBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

}
