<?php
/**
 * Club Hg-Product
 *
 * Shop Products i18n entity
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
 * HgProductBundle\Entity\ShopProductsI18n
 *
 * @ORM\Table(name="shop_products_i18n")
 * @ORM\Entity(repositoryClass="HgProductBundle\Repository\ShopProductsI18n")
 * @ORM\HasLifecycleCallbacks
 */
class ShopProductsI18n
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
     * @ORM\Column(type="string", length=5)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="short_description", nullable=true)
     */
    protected $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="full_description", nullable=true)
     */
    protected $fullDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="meta_title", length=255, nullable=true)
     */
    protected $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="meta_description", length=255, nullable=true)
     */
    protected $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="meta_keywords", length=255, nullable=true)
     */
    protected $metaKeywords;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $fullDescription
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * @param string $locale
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $metaDescription
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaKeywords
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaTitle
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param string $name
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $shortDescription
     * @return \HgProductBundle\Entity\ShopProductsI18n
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

}
