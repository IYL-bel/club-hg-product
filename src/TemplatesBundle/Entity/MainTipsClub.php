<?php
/**
 * Club Hg-Product
 *
 * Main Tips Club entity
 *
 * @package    TemplatesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * TemplatesBundle\Entity\MainTipsClub
 *
 * @ORM\Table(name="template__main_tips_club")
 * @ORM\Entity(repositoryClass="TemplatesBundle\Repository\MainTipsClub")
 * @ORM\HasLifecycleCallbacks
 */
class MainTipsClub
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
     * @ORM\Column(type="smallint", name="num_tip")
     */
    protected $numTip;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=124)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="picture_link", length=255, nullable=true)
     */
    protected $pictureLink;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $link
     * @return \TemplatesBundle\Entity\MainTipsClub
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param int $numTip
     * @return \TemplatesBundle\Entity\MainTipsClub
     */
    public function setNumTip($numTip)
    {
        $this->numTip = $numTip;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumTip()
    {
        return $this->numTip;
    }

    /**
     * @param string $title
     * @return \TemplatesBundle\Entity\MainTipsClub
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
     * @param string $description
     * @return \TemplatesBundle\Entity\MainTipsClub
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
     * @param string $pictureLink
     */
    public function setPictureLink($pictureLink)
    {
        $this->pictureLink = $pictureLink;
    }

    /**
     * @return string
     */
    public function getPictureLink()
    {
        return $this->pictureLink;
    }

}
