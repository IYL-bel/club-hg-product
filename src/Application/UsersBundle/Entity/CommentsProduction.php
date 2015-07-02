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
     * @ORM\Column(type="string", name="name_product", length=150, nullable=true)
     */
    protected $nameProduct;

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
     * @ORM\OneToOne(targetEntity="Application\ScoresBundle\Entity\Scores", inversedBy="commentsProductionScore", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="score_id", referencedColumnName="id", nullable=true)
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

}
