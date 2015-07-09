<?php
/**
 * Club Hg-Product
 *
 * Shop Productions manager
 *
 * @package    HgProductBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace HgProductBundle\Manager;

use Doctrine\ORM\EntityManager;


/**
 * HgProductBundle\Manager\ShopProductions
 */
class ShopProductions
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $emHgProd;


    /**
     * constructor
     *
     * @param \Doctrine\ORM\EntityManager $emHgProd
     */
    public function __construct(EntityManager $emHgProd)
    {
        $this->emHgProd = $emHgProd;
    }

    /**
     * @param array $items
     * @return array
     */
    public function addShopProductData($items)
    {
        $idsShopProducts = array();
        /** @var $item \Application\UsersBundle\Entity\CommentsProduction|\Application\TestProductionBundle\Entity\TestsProduction */
        foreach ($items as $item) {
            if ( $item->getShopProductsI18nId() ) {
                $idsShopProducts[] = $item->getShopProductsI18nId();
            }
        }

        if ($idsShopProducts) {
            $shopProductsRepository = $this->emHgProd->getRepository('HgProductBundle:ShopProducts');
            $shopProducts = $shopProductsRepository->findBy(array('id' => $idsShopProducts));

            $shopProductsRew = array();
            /** @var $shop \HgProductBundle\Entity\ShopProducts */
            foreach ($shopProducts as $shop) {
                $shopProductsRew[$shop->getId()] = $shop;
            }

            /** @var $item \Application\UsersBundle\Entity\CommentsProduction|\Application\TestProductionBundle\Entity\TestsProduction */
            foreach ($items as $item) {
                if ( isset( $shopProductsRew[$item->getShopProductsI18nId()] ) ) {
                    /** @var $itemShopProducts \HgProductBundle\Entity\ShopProducts */
                    $itemShopProducts = $shopProductsRew[$item->getShopProductsI18nId()];
                    $item->setShopProductUrl( 'http://hg-product.ru/shop/product/' . $itemShopProducts->getUrl() );
                    $item->setSmallImage( 'http://hg-product.ru/uploads/shop/' . $itemShopProducts->getSmallImage() );
                }
            }
        }

        return $items;
    }

}
