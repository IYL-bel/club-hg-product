<?php
/**
 * Club Hg-Product
 *
 * Contests Members entity
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\ContestsBundle\Entity\Contests;
use Application\UsersBundle\Entity\Users;
use Application\ContestsBundle\Repository\ContestsMembers as ContestsMembersRepository;
use Application\ContestsBundle\Repository\ContestsVoting as ContestsVotingRepository;


/**
 * Application\ContestsBundle\Entity\ContestsMembers
 *
 * @ORM\Table(name="contests_members")
 * @ORM\Entity(repositoryClass="Application\ContestsBundle\Repository\ContestsMembers")
 * @ORM\HasLifecycleCallbacks
 */
class ContestsMembers
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
     * @var \Application\ContestsBundle\Entity\Contests
     *
     * @ORM\ManyToOne(targetEntity="Contests", inversedBy="contestsMembers")
     * @ORM\JoinColumn(name="contest_id", referencedColumnName="id")
     */
    protected $contest;

    /**
     * @var \Application\UsersBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="contestsMembers")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="comment_admin", length=256, nullable=true)
     */
    protected $commentAdmin;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ContestsMembersPhotos", mappedBy="contestsMember", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="contest_member_id")
     */
    protected $contestsMembersPhotos;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ContestsVoting", mappedBy="contestsMember", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="contests_member_id")
     */
    protected $contestsVoting;

    /**
     * @var \Application\ContestsBundle\Entity\Contests
     *
     * @ORM\OneToOne(targetEntity="Contests", mappedBy="memberWinner")
     */
    protected $contestWinner;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->contestsMembersPhotos = new ArrayCollection();
        $this->contestsVoting = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\ContestsBundle\Entity\Contests $contest
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function setContest(Contests $contest)
    {
        $this->contest = $contest;
        return $this;
    }

    /**
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function getContest()
    {
        return $this->contest;
    }

    /**
     * @param \DateTime $createdAt
     * @return \Application\ContestsBundle\Entity\ContestsMembers
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
     * @return \Application\ContestsBundle\Entity\ContestsMembers
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
     * @param \DateTime $updatedAt
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\ContestsBundle\Entity\ContestsMembers
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
     * @param \Doctrine\Common\Collections\ArrayCollection $contestsMembersPhotos
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function setContestsMembersPhotos($contestsMembersPhotos)
    {
        $this->contestsMembersPhotos = $contestsMembersPhotos;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getContestsMembersPhotos()
    {
        return $this->contestsMembersPhotos;
    }

    /**
     * @param string $commentAdmin
     * @return \Application\ContestsBundle\Entity\ContestsMembers
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
     * @param int $status
     * @return \Application\ContestsBundle\Entity\ContestsMembers
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
        if ($this->status == ContestsMembersRepository::STATUS_NEW ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusConfirmed()
    {
        if ($this->status == ContestsMembersRepository::STATUS_CONFIRMED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStatusRejected()
    {
        if ($this->status == ContestsMembersRepository::STATUS_REJECTED) {
            return true;
        }

        return false;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $contestsVoting
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function setContestsVoting($contestsVoting)
    {
        $this->contestsVoting = $contestsVoting;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getContestsVoting()
    {
        return $this->contestsVoting;
    }

    /**
     * @return array
     */
    public function getContestsVotingFb()
    {
        $contestsVoting = array();
        if ($this->contestsVoting) {
            /** @var \Application\ContestsBundle\Entity\ContestsVoting $voting */
            foreach ($this->contestsVoting as $voting) {
                if ($voting->getType() == ContestsVotingRepository::TYPE_FB) {
                    $contestsVoting[] = $voting;
                }
            }
        }

        return $contestsVoting;
    }

    /**
     * @return array
     */
    public function getContestsVotingVk()
    {
        $contestsVoting = array();
        if ($this->contestsVoting) {
            /** @var \Application\ContestsBundle\Entity\ContestsVoting $voting */
            foreach ($this->contestsVoting as $voting) {
                if ($voting->getType() == ContestsVotingRepository::TYPE_VK) {
                    $contestsVoting[] = $voting;
                }
            }
        }

        return $contestsVoting;
    }

    /**
     * @return array
     */
    public function getContestsVotingOk()
    {
        $contestsVoting = array();
        if ($this->contestsVoting) {
            /** @var \Application\ContestsBundle\Entity\ContestsVoting $voting */
            foreach ($this->contestsVoting as $voting) {
                if ($voting->getType() == ContestsVotingRepository::TYPE_OK) {
                    $contestsVoting[] = $voting;
                }
            }
        }

        return $contestsVoting;
    }

    /**
     * @param \Application\ContestsBundle\Entity\Contests $contestWinner
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function setContestWinner(Contests $contestWinner)
    {
        $this->contestWinner = $contestWinner;
        return $this;
    }

    /**
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function getContestWinner()
    {
        return $this->contestWinner;
    }

}
