<?php
/**
 * Scores Hg-Product
 *
 * Prizes entity
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\AdminBundle\Entity\Contests;


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
     * @var \Application\AdminBundle\Entity\Contests
     *
     * @ORM\OneToOne(targetEntity="Application\AdminBundle\Entity\Contests", mappedBy="scoresParticipation")
     */
    protected $contestsScoresParticipation;

    /**
     * @var \Application\AdminBundle\Entity\Contests
     *
     * @ORM\OneToOne(targetEntity="Application\AdminBundle\Entity\Contests", mappedBy="scoresWinner")
     */
    protected $contestsScoresWinner;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\AdminBundle\Entity\Contests $contestsScoresParticipation
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setContestsScoresParticipation(Contests $contestsScoresParticipation)
    {
        $this->contestsScoresParticipation = $contestsScoresParticipation;
        return $this;
    }

    /**
     * @return \Application\AdminBundle\Entity\Contests
     */
    public function getContestsScoresParticipation()
    {
        return $this->contestsScoresParticipation;
    }

    /**
     * @param \Application\AdminBundle\Entity\Contests $contestsScoresWinner
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function setContestsScoresWinner(Contests $contestsScoresWinner)
    {
        $this->contestsScoresWinner = $contestsScoresWinner;
        return $this;
    }

    /**
     * @return \Application\AdminBundle\Entity\Contests
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

}