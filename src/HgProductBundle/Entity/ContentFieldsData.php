<?php
/**
 * Club Hg-Product
 *
 * Content Fields Data entity
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
 * HgProductBundle\Entity\ContentFieldsData
 *
 * @ORM\Table(name="content_fields_data")
 * @ORM\Entity(repositoryClass="HgProductBundle\Repository\ContentFieldsData")
 * @ORM\HasLifecycleCallbacks
 */
class ContentFieldsData
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
     * @ORM\Column(type="integer", name="item_id")
     */
    protected $itemId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="item_type", length=15)
     */
    protected $itemType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="field_name", length=255)
     */
    protected $fieldName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $data;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $data
     * @return \HgProductBundle\Entity\ContentFieldsData
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $fieldName
     * @return \HgProductBundle\Entity\ContentFieldsData
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param int $itemId
     * @return \HgProductBundle\Entity\ContentFieldsData
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param string $itemType
     * @return \HgProductBundle\Entity\ContentFieldsData
     */
    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }

}
