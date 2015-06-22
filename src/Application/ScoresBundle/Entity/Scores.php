<?php
/**
 * Club Hg-Product
 *
 * Scores entity
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\ContestsBundle\Entity\Contests;


/**
 * Application\ScoresBundle\Entity\Scores
 *
 * @ORM\Table(name="scores")
 * @ORM\Entity(repositoryClass="Application\ScoresBundle\Repository\Scores")
 * @ORM\HasLifecycleCallbacks
 */
class Scores
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $points;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @var \Application\ContestsBundle\Entity\Contests
     *
     * @ORM\OneToOne(targetEntity="Application\ContestsBundle\Entity\Contests", mappedBy="scoresParticipation")
     */
    protected $contestsScoresParticipation;

    /**
     * @var \Application\ContestsBundle\Entity\Contests
     *
     * @ORM\OneToOne(targetEntity="Application\ContestsBundle\Entity\Contests", mappedBy="scoresWinner")
     */
    protected $contestsScoresWinner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ScoresUsers", mappedBy="score", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="score_id")
     */
    protected $scoresUsers;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->scoresUsers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\ContestsBundle\Entity\Contests $contestsScoresParticipation
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setContestsScoresParticipation(Contests $contestsScoresParticipation)
    {
        $this->contestsScoresParticipation = $contestsScoresParticipation;
        return $this;
    }

    /**
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function getContestsScoresParticipation()
    {
        return $this->contestsScoresParticipation;
    }

    /**
     * @param \Application\ContestsBundle\Entity\Contests $contestsScoresWinner
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setContestsScoresWinner(Contests $contestsScoresWinner)
    {
        $this->contestsScoresWinner = $contestsScoresWinner;
        return $this;
    }

    /**
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function getContestsScoresWinner()
    {
        return $this->contestsScoresWinner;
    }

    /**
     * @param int $points
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $type
     * @return \Application\ScoresBundle\Entity\Scores
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
     * @param \Doctrine\Common\Collections\ArrayCollection $scoresUsers
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setScoresUsers($scoresUsers)
    {
        $this->scoresUsers = $scoresUsers;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getScoresUsers()
    {
        return $this->scoresUsers;
    }

}
