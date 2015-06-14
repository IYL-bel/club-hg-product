<?php
/**
 * Club Hg-Product
 *
 * Main Slider entity
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * TemplatesBundle\Entity\MainSlider
 *
 * @ORM\Table(name="template__main_slider")
 * @ORM\Entity(repositoryClass="TemplatesBundle\Repository\MainSlider")
 * @ORM\HasLifecycleCallbacks
 */
class MainSlider
{

    /**
     * Path to Folder for Files Checks
     *
     * @var string
     */
    protected static $webPath = 'uploads/templates';

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
     * @ORM\Column(type="smallint", name="num_slide", nullable=false)
     */
    protected $numSlide;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $text;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="text_color", length=8, nullable=true)
     */
    protected $textColor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="picture_path", length=64, nullable=true)
     */
    protected $picturePath;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $pictureFile;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $numSlide
     */
    public function setNumSlide($numSlide)
    {
        $this->numSlide = $numSlide;
    }

    /**
     * @return int
     */
    public function getNumSlide()
    {
        return $this->numSlide;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $pictureFile
     */
    public function setPictureFile($pictureFile)
    {
        $this->pictureFile = $pictureFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @param string $picturePath
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;
    }

    /**
     * @return string
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * web path file + name file
     *
     * @return null|string
     */
    public function getPictureFilePathWeb()
    {
        return null === $this->picturePath ? null : $this->getUploadDir() . '/' . $this->picturePath;
    }

    /**
     * Manage upload
     *
     * @return null|string
     */
    public function getPictureFileAbsolutePath()
    {
        return null === $this->picturePath ? null : $this->getUploadRootDir( $this->getUploadDir() ) . '/' . $this->picturePath;
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
        return __DIR__.'/../../../web/' . $pathFile;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ( null !== $this->pictureFile ) {
            $this->pictureFile->move( $this->getUploadRootDir( $this->getUploadDir() ), $this->picturePath );
            chmod($this->getPictureFileAbsolutePath(), 0775);
            unset( $this->pictureFile );
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if ( null !== $this->pictureFile ) {
            // do whatever you want to generate a unique name
            $this->setPicturePath( 'main-slider-' . $this->getNumSlide() . '.' . $this->pictureFile->guessExtension() );
        }
    }

}
