<?php
/**
 * Club Hg-Product
 *
 * Prizes entity
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\AdminBundle\Repository\Prizes as PrizesRepository;


/**
 * Application\AdminBundle\Entity\Prizes
 *
 * @ORM\Table(name="prizes")
 * @ORM\Entity(repositoryClass="Application\AdminBundle\Repository\Prizes")
 * @ORM\HasLifecycleCallbacks
 */
class Prizes
{

    /**
     * Path to Folder for Files Prizes
     *
     * @var string
     */
    protected static $webPath = 'uploads/prizes';

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
     * @ORM\Column(type="string", name="title_color", length=8, nullable=true)
     */
    protected $titleColor;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $type;

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
     * @var bool
     *
     * @ORM\Column(type="boolean", name="awarded_scores")
     */
    protected $awardedScores = false;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @param int $type
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @param string $preName
     * @return string
     */
    public function getTypeString($preName = 'admin.form.edit_prize.types_label.')
    {
        $data = PrizesRepository::getNamesType($preName);

        return $data[$this->type];
    }

    /**
     * @param string $filePath
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @return \Application\AdminBundle\Entity\Prizes
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
        if ($this->status == PrizesRepository::STATUS_ACTIVE ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @return \Application\AdminBundle\Entity\Prizes
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
     * @param boolean $awardedScores
     * @return \Application\AdminBundle\Entity\Prizes
     */
    public function setAwardedScores($awardedScores)
    {
        $this->awardedScores = $awardedScores;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAwardedScores()
    {
        return $this->awardedScores;
    }

    /**
     * @param string $titleColor
     * @return \Application\AdminBundle\Entity\Prizes
     */
    public function setTitleColor($titleColor)
    {
        $this->titleColor = $titleColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitleColor()
    {
        return $this->titleColor;
    }

}
