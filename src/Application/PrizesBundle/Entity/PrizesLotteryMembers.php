<?php
/**
 * Club Hg-Product
 *
 * Prizes Lottery Members entity
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\PrizesBundle\Entity\PrizesLottery;
use Application\UsersBundle\Entity\Users;


/**
 * Application\PrizesBundle\Entity\PrizesLotteryMembers
 *
 * @ORM\Table(name="prizes_lottery_members")
 * @ORM\Entity(repositoryClass="Application\PrizesBundle\Repository\PrizesLotteryMembers")
 * @ORM\HasLifecycleCallbacks
 */
class PrizesLotteryMembers
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
     * @var \Application\PrizesBundle\Entity\PrizesLottery
     *
     * @ORM\ManyToOne(targetEntity="PrizesLottery", inversedBy="prizesLotteryMembers")
     * @ORM\JoinColumn(name="prize_lottery__id", referencedColumnName="id")
     */
    protected $prizeLottery;

    /**
     * @var \Application\UsersBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="prizesLotteryMembers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \Application\PrizesBundle\Entity\PrizesLottery
     *
     * @ORM\OneToOne(targetEntity="PrizesLottery", mappedBy="memberWinner")
     */
    protected $prizesLotteryWinner;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $createdAt
     * @return \Application\PrizesBundle\Entity\PrizesLotteryMembers
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
     * @param \Application\PrizesBundle\Entity\PrizesLottery $prizeLottery
     * @return \Application\PrizesBundle\Entity\PrizesLotteryMembers
     */
    public function setPrizeLottery(PrizesLottery $prizeLottery)
    {
        $this->prizeLottery = $prizeLottery;
        return $this;
    }

    /**
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function getPrizeLottery()
    {
        return $this->prizeLottery;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\PrizesBundle\Entity\PrizesLotteryMembers
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
     * @param \Application\PrizesBundle\Entity\PrizesLottery $prizesLotteryWinner
     * @return \Application\PrizesBundle\Entity\PrizesLotteryMembers
     */
    public function setPrizesLotteryWinner(PrizesLottery $prizesLotteryWinner)
    {
        $this->prizesLotteryWinner = $prizesLotteryWinner;
        return $this;
    }

    /**
     * @return \Application\PrizesBundle\Entity\PrizesLottery
     */
    public function getPrizesLotteryWinner()
    {
        return $this->prizesLotteryWinner;
    }

}
