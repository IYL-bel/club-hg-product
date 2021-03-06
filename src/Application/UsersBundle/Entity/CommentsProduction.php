<?php
/**
 * Club Hg-Product
 *
 * Comments Production entity
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\UsersBundle\Entity\Users;
use Application\ScoresBundle\Entity\Scores;
use Application\UsersBundle\Repository\CommentsProduction as CommentsProductionRepository;
use Application\TestProductionBundle\Entity\TestsProduction;


/**
 * Application\UsersBundle\Entity\CommentsProduction
 *
 * @ORM\Table(name="comments_production")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\CommentsProduction")
 * @ORM\HasLifecycleCallbacks
 */
class CommentsProduction
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
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="commentsProduction")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
     * @ORM\Column(name="processing_at", type="datetime", nullable=true)
     */
    protected $processingAt;

    /**
     * @var \Application\ScoresBundle\Entity\Scores
     *
     * @ORM\ManyToOne(targetEntity="Application\ScoresBundle\Entity\Scores", inversedBy="commentsProduction")
     * @ORM\JoinColumn(name="score_id", referencedColumnName="id")
     */
    protected $score;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CommentsProductionPhotos", mappedBy="commentsProduction", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="comments_production_id")
     */
    protected $commentsProductionPhotos;

    /**
     * @var \Application\TestProductionBundle\Entity\TestsProduction
     *
     * @ORM\OneToOne(targetEntity="Application\TestProductionBundle\Entity\TestsProduction", mappedBy="commentProduction")
     */
    protected $testProduction;

    /**
     * @var bool
     *
     * @ORM\Column(name="after_testing", type="boolean", nullable=true)
     */
    protected $afterTesting = false;

    /**
     * @var null|string
     */
    protected $shopProductUrl = null;

    /**
     * @var null|string
     */
    protected $smallImage = null;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->commentsProductionPhotos = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\UsersBundle\Entity\Checks
     */
    public function setUser(Users $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \Application\UsersBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $commentAdmin
     * @return \Application\UsersBundle\Entity\CommentsProduction
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

    /**
     * @param \DateTime $createdAt
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $description
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $nameProduct
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setNameProduct($nameProduct)
    {
        $this->nameProduct = $nameProduct;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameProduct()
    {
        return $this->nameProduct;
    }

    /**
     * @param \DateTime $processingAt
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setProcessingAt(\DateTime $processingAt)
    {
        $this->processingAt = $processingAt;
        return$this;
    }

    /**
     * @return \DateTime
     */
    public function getProcessingAt()
    {
        return $this->processingAt;
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $score
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setScore(Scores $score)
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
     * @param int $status
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isStatusNew()
    {
        if ($this->status == CommentsProductionRepository::STATUS_NEW ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusConfirmed()
    {
        if ($this->status == CommentsProductionRepository::STATUS_CONFIRMED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusRejected()
    {
        if ($this->status == CommentsProductionRepository::STATUS_REJECTED) {
            return true;
        }

        return false;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $commentsProductionPhotos
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setCommentsProductionPhotos($commentsProductionPhotos)
    {
        $this->commentsProductionPhotos = $commentsProductionPhotos;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentsProductionPhotos()
    {
        return $this->commentsProductionPhotos;
    }

    /**
     * @param int $shopProductsI18nId
     * @return \Application\UsersBundle\Entity\CommentsProduction
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
     * @param null|string $shopProductUrl
     * @return \Application\UsersBundle\Entity\CommentsProduction
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
     * @return \Application\UsersBundle\Entity\CommentsProduction
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
     * @param \Application\TestProductionBundle\Entity\TestsProduction $testProduction
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function setTestProduction(TestsProduction $testProduction)
    {
        $this->testProduction = $testProduction;
        return $this;
    }

    /**
     * @return \Application\TestProductionBundle\Entity\TestsProduction
     */
    public function getTestProduction()
    {
        return $this->testProduction;
    }

    /**
     * @param boolean $afterTesting
     */
    public function setAfterTesting($afterTesting)
    {
        $this->afterTesting = $afterTesting;
    }

    /**
     * @return boolean
     */
    public function isAfterTesting()
    {
        return $this->afterTesting;
    }

}
