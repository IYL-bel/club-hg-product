<?php
/**
 * Club Hg-Product
 *
 * Comments entity
 *
 * @package    HgProductBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace HgProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * HgProductBundle\Entity\Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="HgProductBundle\Repository\Comments")
 * @ORM\HasLifecycleCallbacks
 */
class Comments
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
     * @ORM\Column(type="string", length=25)
     */
    protected $module;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="user_id")
     */
    protected $userId = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="user_name", length=50)
     */
    protected $userName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="user_mail", length=50)
     */
    protected $userMail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="user_site", length=250)
     */
    protected $userSite = 'on';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="item_id")
     */
    protected $itemId;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $date;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $status = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250)
     */
    protected $agent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="user_ip", length=64)
     */
    protected $userIp = '127.0.0.1';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $rate = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="text_plus", length=500, nullable=true)
     */
    protected $textPlus;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="text_minus", length=500, nullable=true)
     */
    protected $textMinus;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $like = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $disslike = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $parent = 0;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param int $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $disslike
     */
    public function setDisslike($disslike)
    {
        $this->disslike = $disslike;
    }

    /**
     * @return int
     */
    public function getDisslike()
    {
        return $this->disslike;
    }

    /**
     * @param int $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int $like
     */
    public function setLike($like)
    {
        $this->like = $like;
    }

    /**
     * @return int
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
     * @param string $textMinus
     */
    public function setTextMinus($textMinus)
    {
        $this->textMinus = $textMinus;
    }

    /**
     * @return string
     */
    public function getTextMinus()
    {
        return $this->textMinus;
    }

    /**
     * @param string $textPlus
     */
    public function setTextPlus($textPlus)
    {
        $this->textPlus = $textPlus;
    }

    /**
     * @return string
     */
    public function getTextPlus()
    {
        return $this->textPlus;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userIp
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    }

    /**
     * @return string
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @param string $userMail
     */
    public function setUserMail($userMail)
    {
        $this->userMail = $userMail;
    }

    /**
     * @return string
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userSite
     */
    public function setUserSite($userSite)
    {
        $this->userSite = $userSite;
    }

    /**
     * @return string
     */
    public function getUserSite()
    {
        return $this->userSite;
    }


}
