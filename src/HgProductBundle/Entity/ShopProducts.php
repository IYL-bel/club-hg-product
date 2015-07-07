<?php
/**
 * Club Hg-Product
 *
 * Shop Products entity
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
 * HgProductBundle\Entity\ShopProducts
 *
 * @ORM\Table(name="shop_products")
 * @ORM\Entity(repositoryClass="HgProductBundle\Repository\ShopProducts")
 * @ORM\HasLifecycleCallbacks
 */
class ShopProducts
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
     * @ORM\Column(type="string", length=255)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $smallImage;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $smallImage
     * @return \HgProductBundle\Entity\ShopProducts
     */
    public function setSmallImage($smallImage)
    {
        $this->smallImage = $smallImage;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmallImage()
    {
        return $this->smallImage;
    }

    /**
     * @param string $url
     * @return \HgProductBundle\Entity\ShopProducts
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}
