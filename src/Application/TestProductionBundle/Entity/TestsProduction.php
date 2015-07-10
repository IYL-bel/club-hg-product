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

use Application\TestProductionBundle\Repository\TestsProduction as TestsProductionRepository;


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
     * @ORM\Column(type="string", name="name_product", length=500, nullable=true)
     */
    protected $nameProduct;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="shop_products_i18n__id", nullable=true)
     */
    protected $shopProductsI18nId;

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
     * @var string
     *
     * @ORM\Column(type="string", name="comment_admin", length=256, nullable=true)
     */
    protected $commentAdmin;

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
     * @var \DateTime
     *
     * @ORM\Column(name="processing_at", type="datetime", nullable=true)
     */
    protected $processingAt;

    /**
     * @var \Application\ScoresBundle\Entity\Scores
     *
     * @ORM\ManyToOne(targetEntity="Application\ScoresBundle\Entity\Scores", inversedBy="testsProduction")
     * @ORM\JoinColumn(name="score_id", referencedColumnName="id", nullable=true)
     */
    protected $score;

    /**
     * @var \Application\UsersBundle\Entity\CommentsProduction
     *
     * @ORM\OneToOne(targetEntity="Application\UsersBundle\Entity\CommentsProduction", inversedBy="testProduction", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="comment_production__id", referencedColumnName="id", nullable=true)
     */
    protected $commentProduction;

    /**
     * @var null|string
     */
    protected $shopProductUrl = null;

    /**
     * @var null|string
     */
    protected $smallImage = null;


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
     * @return bool
     */
    public function isStatusNew()
    {
        if ($this->status == TestsProductionRepository::STATUS_NEW ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusConfirmed()
    {
        if ($this->status == TestsProductionRepository::STATUS_CONFIRMED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusRejected()
    {
        if ($this->status == TestsProductionRepository::STATUS_REJECTED) {
            return true;
        }

        return false;
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

    /**
     * @param int $shopProductsI18nId
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setShopProductsI18nId($shopProductsI18nId)
    {
        $this->shopProductsI18nId = $shopProductsI18nId;
        return $this;
    }

    /**
     * @return int
     */
    public function getShopProductsI18nId()
    {
        return $this->shopProductsI18nId;
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $score
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param \Application\UsersBundle\Entity\CommentsProduction $commentProduction
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setCommentProduction($commentProduction)
    {
        $this->commentProduction = $commentProduction;
        return $this;
    }

    /**
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function getCommentProduction()
    {
        return $this->commentProduction;
    }

    /**
     * @param \DateTime $processingAt
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setProcessingAt($processingAt)
    {
        $this->processingAt = $processingAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getProcessingAt()
    {
        return $this->processingAt;
    }

    /**
     * @param null|string $shopProductUrl
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setShopProductUrl($shopProductUrl)
    {
        $this->shopProductUrl = $shopProductUrl;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getShopProductUrl()
    {
        return $this->shopProductUrl;
    }

    /**
     * @param null|string $smallImage
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setSmallImage($smallImage)
    {
        $this->smallImage = $smallImage;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSmallImage()
    {
        return $this->smallImage;
    }

    /**
     * @param string $commentAdmin
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function setCommentAdmin($commentAdmin)
    {
        $this->commentAdmin = $commentAdmin;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentAdmin()
    {
        return $this->commentAdmin;
    }

}
