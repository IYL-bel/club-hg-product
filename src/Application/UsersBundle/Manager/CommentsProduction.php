<?php
/**
 * Club Hg-Product
 *
 * Comments Production manager
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Manager;

use Doctrine\ORM\EntityManager;


/**
 * Application\UsersBundle\Manager\CommentsProduction
 */
class CommentsProduction
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
     * @param array $commentsProduct
     * @return array
     */
    public function addShopProductData($commentsProduct)
    {
        $idsShopProducts = array();
        /** @var $comment \Application\UsersBundle\Entity\CommentsProduction */
        foreach ($commentsProduct as $comment) {
            if ( $comment->getShopProductsI18nId() ) {
                $idsShopProducts[] = $comment->getShopProductsI18nId();
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

            /** @var $comment \Application\UsersBundle\Entity\CommentsProduction */
            foreach ($commentsProduct as $comment) {
                if ( isset( $shopProductsRew[$comment->getShopProductsI18nId()] ) ) {
                    /** @var $itemShopProducts \HgProductBundle\Entity\ShopProducts */
                    $itemShopProducts = $shopProductsRew[$comment->getShopProductsI18nId()];
                    $comment->setShopProductUrl( $itemShopProducts->getUrl() );
                    $comment->setSmallImage( $itemShopProducts->getSmallImage() );
                }
            }
        }

        return $commentsProduct;
    }

}
