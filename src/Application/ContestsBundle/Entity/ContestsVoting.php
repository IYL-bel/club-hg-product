<?php
/**
 * Club Hg-Product
 *
 * Contests Voting entity
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

use Application\UsersBundle\Entity\Users;
use Application\ContestsBundle\Entity\ContestsMembers;


/**
 * Application\ContestsBundle\Entity\ContestsVoting
 *
 * @ORM\Table(name="contests_voting")
 * @ORM\Entity(repositoryClass="Application\ContestsBundle\Repository\ContestsVoting")
 * @ORM\HasLifecycleCallbacks
 */
class ContestsVoting
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
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="contestsVoting")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Application\ContestsBundle\Entity\ContestsMembers
     *
     * @ORM\ManyToOne(targetEntity="ContestsMembers", inversedBy="contestsVoting")
     * @ORM\JoinColumn(name="contests_member_id", referencedColumnName="id")
     */
    protected $contestsMember;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $createdAt
     * @return \Application\ContestsBundle\Entity\Contests
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
     * @param \Application\ContestsBundle\Entity\ContestsMembers $contestsMember
     * @return \Application\ContestsBundle\Entity\ContestsVoting
     */
    public function setContestsMember(ContestsMembers $contestsMember)
    {
        $this->contestsMember = $contestsMember;
        return $this;
    }

    /**
     * @return \Application\ContestsBundle\Entity\ContestsMembers
     */
    public function getContestsMember()
    {
        return $this->contestsMember;
    }

    /**
     * @param int $type
     * @return \Application\ContestsBundle\Entity\ContestsVoting
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\ContestsBundle\Entity\ContestsVoting
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

}
