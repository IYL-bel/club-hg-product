<?php
/**
 * Club Hg-Product
 *
 * Users entity
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

use Gedmo\Mapping\Annotation as Gedmo;





/**
 * Application\UsersBundle\Entity\Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\Users")
 * @ORM\HasLifecycleCallbacks
 */
class Users extends BaseUser
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
     * @ORM\Column(name="type_user", type="integer", nullable=false)
     */
    protected $typeUser;

    /**
     * @var null|int
     *
     * @ORM\Column(name="fb_id", type="bigint",  nullable=true)
     */
    protected $fbId;

    /**
     * @var null|int
     *
     * @ORM\Column(name="vk_id", type="bigint",  nullable=true)
     */
    protected $vkId;

    /**
     * @var null|int
     *
     * @ORM\Column(name="ok_id", type="bigint",  nullable=true)
     */
    protected $okId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="first_name", length=32, nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="last_name", length=32, nullable=false)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="photo_link", length=128, nullable=true)
     */
    protected $photoLink;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $link;

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
     * @ORM\Column(name="update_at", type="datetime")
     */
    protected $updateAt;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $typeUser
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setTypeUser($typeUser)
    {
        $this->typeUser = $typeUser;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeUser()
    {
        return $this->typeUser;
    }

    /**
     * @param int|null $fbId
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * @param int|null $vkId
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setVkId($vkId)
    {
        $this->vkId = $vkId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVkId()
    {
        return $this->vkId;
    }

    /**
     * @param int|null $okId
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setOkId($okId)
    {
        $this->okId = $okId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOkId()
    {
        return $this->okId;
    }

    /**
     * @param string $firstName
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $photoLink
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setPhotoLink($photoLink)
    {
        $this->photoLink = $photoLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoLink()
    {
        return $this->photoLink;
    }

    /**
     * @param string $link
     * @return \Application\UsersBundle\Entity\Users
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
     * @param \DateTime $createdAt
     * @return \Application\UsersBundle\Entity\Users
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
     * @param \DateTime $updateAt
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param string $slug
     * @return \Application\UsersBundle\Entity\Users
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
