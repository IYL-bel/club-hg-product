<?php
/**
 * Club Hg-Product
 *
 * Scores Users entity
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\UsersBundle\Entity\Users;
use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Repository\ScoresUsers as ScoresUsersRepository;


/**
 * Application\ScoresBundle\Entity\ScoresUsers
 *
 * @ORM\Table(name="scores_users")
 * @ORM\Entity(repositoryClass="Application\ScoresBundle\Repository\ScoresUsers")
 * @ORM\HasLifecycleCallbacks
 */
class ScoresUsers
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
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="scoresUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Application\ScoresBundle\Entity\Scores
     *
     * @ORM\ManyToOne(targetEntity="Scores", inversedBy="scoresUsers")
     * @ORM\JoinColumn(name="score_id", referencedColumnName="id")
     */
    protected $score;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="type_calculation", type="smallint", nullable=true)
     */
    protected $typeCalculation;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\ScoresBundle\Entity\ScoresUsers
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
     * @param \Application\ScoresBundle\Entity\Scores $score
     * @return \Application\ScoresBundle\Entity\ScoresUsers
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
     * @param \DateTime $createdAt
     * @return \Application\ScoresBundle\Entity\ScoresUsers
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
     * @param int $typeCalculation
     * @return \Application\ScoresBundle\Entity\ScoresUsers
     */
    public function setTypeCalculation($typeCalculation)
    {
        $this->typeCalculation = $typeCalculation;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeCalculation()
    {
        return $this->typeCalculation;
    }

    /**
     * @param int $points
     * @return int
     */
    public function changeCalculate($points)
    {
        if ($this->getTypeCalculation() ==  ScoresUsersRepository::TYPE_CALCULATION__SUBTRACTION) {
            $points = $points - $this->getScore()->getPoints();
        } else {
            $points = $points + $this->getScore()->getPoints();
        }

        return $points;
    }

}
