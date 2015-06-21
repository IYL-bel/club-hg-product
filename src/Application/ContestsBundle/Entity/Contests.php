<?php
/**
 * Club Hg-Product
 *
 * Contests entity
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

use Application\ContestsBundle\Repository\Contests as ContestsRepository;
use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Repository\Scores as ScoresRepository;


/**
 * Application\ContestsBundle\Entity\Contests
 *
 * @ORM\Table(name="contests")
 * @ORM\Entity(repositoryClass="Application\ContestsBundle\Repository\Contests")
 * @ORM\HasLifecycleCallbacks
 */
class Contests
{

    /**
     * Path to Folder for Files Prizes
     *
     * @var string
     */
    protected static $webPath = 'uploads/contests';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="file_path", length=100, nullable=true)
     */
    protected $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=100, nullable=true)
     */
    protected $fileName;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

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
     * @var \Application\ScoresBundle\Entity\Scores
     *
     * @ORM\OneToOne(targetEntity="Application\ScoresBundle\Entity\Scores", mappedBy="contestsScoresParticipation", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="scores_participation__id", referencedColumnName="id", nullable=true)
     */
    protected $scoresParticipation;

    /**
     * @var int
     */
    protected $pointsParticipation;

    /**
     * @var \Application\ScoresBundle\Entity\Scores
     *
     * @ORM\OneToOne(targetEntity="Application\ScoresBundle\Entity\Scores", mappedBy="contestsScoresWinner", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="scores_winner__id", referencedColumnName="id", nullable=true)
     */
    protected $scoresWinner;

    /**
     * @var int
     */
    protected $pointsWinner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ContestsMembers", mappedBy="contest", cascade={"persist","remove","merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="contest_id")
     */
    protected $contestsMembers;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->contestsMembers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $filePath
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $fileName
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * web path file + name file
     *
     * @return null|string
     */
    public function getFilePathWeb()
    {
        return null === $this->filePath ? null : $this->getUploadDir() . '/' . $this->filePath;
    }

    /**
     * Manage upload
     *
     * @return null|string
     */
    public function getFileAbsolutePath()
    {
        return null === $this->filePath ? null : $this->getUploadRootDir( $this->getUploadDir() ) . '/' . $this->filePath;
    }

    /**
     * @return string
     */
    public static function getFileFullPath()
    {
        return  __DIR__.'/../../../../web/' . self::$webPath;
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return self::$webPath;
    }

    /**
     * @param string $pathFile
     * @return string
     */
    protected function getUploadRootDir($pathFile)
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/' . $pathFile;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ( null !== $this->file ) {
            $this->file->move($this->getUploadRootDir( $this->getUploadDir() ), $this->filePath );
            unset( $this->file );
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if ( null !== $this->file ) {
            // do whatever you want to generate a unique name
            $this->setFilePath( uniqid() . '.' . $this->file->guessExtension() );
            $this->setFileName( $this->file->getClientOriginalName() );
        }
    }

    /**
     * @param int $status
     * @return \Application\ContestsBundle\Entity\Contests
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
    public function isStatusActive()
    {
        if ($this->status == ContestsRepository::STATUS_ACTIVE ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \DateTime $createdAt
     * @return \Application\ContestsBundle\Entity\Contests
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
     * @param string $description
     * @return \Application\ContestsBundle\Entity\Contests
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
     * @param \DateTime $finishedAt
     * @return \Application\ContestsBundle\Entity\Contests
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
     * @param \DateTime $startedAt
     * @return \Application\ContestsBundle\Entity\Contests
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
     * @param \DateTime $updatedAt
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setUpdatedAt($updatedAt)
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
     * @param int $pointsParticipation
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setPointsParticipation($pointsParticipation)
    {
        $this->pointsParticipation = $pointsParticipation;
        return $this;
    }

    /**
     * @return int
     */
    public function getPointsParticipation()
    {
        return $this->pointsParticipation;
    }

    /**
     * @param int $pointsWinner
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setPointsWinner($pointsWinner)
    {
        $this->pointsWinner = $pointsWinner;
        return $this;
    }

    /**
     * @return int
     */
    public function getPointsWinner()
    {
        return $this->pointsWinner;
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $scoresParticipation
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setScoresParticipation(Scores $scoresParticipation)
    {
        $this->scoresParticipation = $scoresParticipation;
        return $this;
    }

    /**
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function getScoresParticipation()
    {
        return $this->scoresParticipation;
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $scoresWinner
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setScoresWinner(Scores $scoresWinner)
    {
        $this->scoresWinner = $scoresWinner;
        return $this;
    }

    /**
     * @return \Application\ScoresBundle\Entity\Scores
     */
    public function getScoresWinner()
    {
        return $this->scoresWinner;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $contestsMembers
     * @return \Application\ContestsBundle\Entity\Contests
     */
    public function setContestsMembers($contestsMembers)
    {
        $this->contestsMembers = $contestsMembers;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getContestsMembers()
    {
        return $this->contestsMembers;
    }

}
