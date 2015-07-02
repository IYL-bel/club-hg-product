<?php
/**
 * Club Hg-Product
 *
 * Comments Production Photos entity
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Application\UsersBundle\Entity\CommentsProduction;


/**
 * Application\UsersBundle\Entity\CommentsProductionPhotos
 *
 * @ORM\Table(name="comments_production_photos")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\CommentsProductionPhotos")
 * @ORM\HasLifecycleCallbacks
 */
class CommentsProductionPhotos
{

    /**
     * Path to Folder for Files Prizes
     *
     * @var string
     */
    protected static $webPath = 'uploads/comments';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\UsersBundle\Entity\CommentsProduction
     *
     * @ORM\ManyToOne(targetEntity="CommentsProduction", inversedBy="commentsProductionPhotos")
     * @ORM\JoinColumn(name="comments_production_id", referencedColumnName="id")
     */
    protected $commentsProduction;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @static
     * @return string
     */
    public static function getWebPath()
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
     * @param \Application\UsersBundle\Entity\CommentsProduction $commentsProduction
     * @return \Application\UsersBundle\Entity\CommentsProductionPhotos
     */
    public function setCommentsProduction(CommentsProduction $commentsProduction)
    {
        $this->commentsProduction = $commentsProduction;
        return $this;
    }

    /**
     * @return \Application\UsersBundle\Entity\CommentsProduction
     */
    public function getCommentsProduction()
    {
        return $this->commentsProduction;
    }

}
