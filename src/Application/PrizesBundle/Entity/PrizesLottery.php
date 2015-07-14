<?php
/**
 * Club Hg-Product
 *
 * Prizes Lottery entity
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Application\PrizesBundle\Entity\PrizesLottery
 *
 * @ORM\Table(name="prizes_lottery")
 * @ORM\Entity(repositoryClass="Application\PrizesBundle\Repository\PrizesLottery")
 * @ORM\HasLifecycleCallbacks
 */
class PrizesLottery
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
     * @var \Application\PrizesBundle\Entity\Prizes
     *
     * @ORM\ManyToOne(targetEntity="Prizes", inversedBy="prizesLottery")
     * @ORM\JoinColumn(name="prize_id", referencedColumnName="id")
     */
    protected $prize;

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
     * @ORM\Column(name="started_at", type="datetime")
     */
    protected $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_at", type="datetime")
     */
    protected $finishedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PrizesLotteryMembers", mappedBy="prizeLottery", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="prize_lottery__id")
     */
    protected $prizesLotteryMembers;

    /**
     * @var \Application\PrizesBundle\Entity\PrizesLotteryMembers
     *
     * @ORM\OneToOne(targetEntity="PrizesLotteryMembers", inversedBy="prizesLotteryWinner", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="member_winner__id", referencedColumnName="id", nullable=true)
     */
    protected $memberWinner;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->prizesLotteryMembers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\PrizesBundle\Entity\Prizes $prize
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setPrize($prize)
    {
        $this->prize = $prize;
        return $this;
    }

    /**
     * @return \Application\PrizesBundle\Entity\Prizes
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * @param \DateTime $createdAt
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setCreatedAt($createdAt)
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
     * @param \DateTime $startedAt
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $finishedAt
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param int $status
     * @return \Application\PrizesBundle\Entity\PrizesLottery
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
     * @param \Doctrine\Common\Collections\ArrayCollection $prizesLotteryMembers
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setPrizesLotteryMembers($prizesLotteryMembers)
    {
        $this->prizesLotteryMembers = $prizesLotteryMembers;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPrizesLotteryMembers()
    {
        return $this->prizesLotteryMembers;
    }

    /**
     * @param \Application\PrizesBundle\Entity\PrizesLotteryMembers $memberWinner
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function setMemberWinner(PrizesLotteryMembers $memberWinner)
    {
        $this->memberWinner = $memberWinner;
        return $this;
    }

    /**
     * @return \Application\PrizesBundle\Entity\PrizesLotteryMembers
     */
    public function getMemberWinner()
    {
        return $this->memberWinner;
    }

}
