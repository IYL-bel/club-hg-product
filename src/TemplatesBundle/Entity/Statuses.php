<?php
/**
 * Club Hg-Product
 *
 * Statuses entity
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
 * TemplatesBundle\Entity\Statuses
 *
 * @ORM\Table(name="template__statuses")
 * @ORM\Entity(repositoryClass="TemplatesBundle\Repository\Statuses")
 * @ORM\HasLifecycleCallbacks
 */
class Statuses
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
     * @var string
     *
     * @ORM\Column(type="string", name="name_status", length=16, nullable=false)
     */
    protected $nameStatus;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $scores;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $description
     * @return \TemplatesBundle\Entity\Statuses
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
     * @param string $nameStatus
     * @return \TemplatesBundle\Entity\Statuses
     */
    public function setNameStatus($nameStatus)
    {
        $this->nameStatus = $nameStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameStatus()
    {
        return $this->nameStatus;
    }

    /**
     * @param int $scores
     * @return \TemplatesBundle\Entity\Statuses
     */
    public function setScores($scores)
    {
        $this->scores = $scores;
        return $this;
    }

    /**
     * @return int
     */
    public function getScores()
    {
        return $this->scores;
    }

}
