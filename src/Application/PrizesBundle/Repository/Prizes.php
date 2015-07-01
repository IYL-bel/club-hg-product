<?php
/**
 * Club Hg-Product
 *
 * Prizes repository
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  Application\PrizesBundle\Repository\Prizes
 */
class Prizes extends EntityRepository
{

    const TYPE_BRONZE     = 1;
    const TYPE_SILVER     = 2;
    const TYPE_GOLD       = 3;
    const TYPE_PLATINUM   = 4;


    const STATUS_ACTIVE  = 1;
    const STATUS_HIDDEN  = 2;


    /**
     * @static
     * @param string $preName
     * @return array
     */
    static public function getNamesType($preName = '')
    {
        return array(
            self::TYPE_BRONZE    => $preName . 'bronze',
            self::TYPE_SILVER    => $preName . 'silver',
            self::TYPE_GOLD      => $preName . 'gold',
            self::TYPE_PLATINUM  => $preName . 'platinum',
        );
    }

    /**
     * @return array
     */
    public function getIdsAllPrizes()
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select(array('p.id'))
            ->where('p.status = :status')
            ->setParameters(array(
                'status' => self::STATUS_ACTIVE,
            ));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getPrizesForMainPage($ids)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where( $qb->expr()->in('p.id', $ids) );

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $type
     * @return array
     */
    public function getPrizesForType($type)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where('p.status = :status')
            ->andWhere('p.type = :type')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameters(array(
                'status' => self::STATUS_ACTIVE,
                'type' => $type
            ));

        return $qb->getQuery()->getResult();
    }

}
