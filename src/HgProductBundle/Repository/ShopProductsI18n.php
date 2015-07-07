<?php
/**
 * Club Hg-Product
 *
 * Shop Products i18n repository
 *
 * @package    HgProductBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace HgProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * HgProductBundle\Repository\ShopProductsI18n
 */
class ShopProductsI18n extends EntityRepository
{

    /**
     * @param string $value
     * @return array
     */
    public function getLikeName($value)
    {
        $qb = $this->createQueryBuilder('sp');
        $qb
            ->select( array('sp.name', 'sp.id') )
            ->where('sp.name LIKE :name')
            ->setParameters(array(
                'name' => '%' . $value . '%',
            ))
            ->setMaxResults(16)
        ;

        return $qb->getQuery()->getResult();
    }

}
