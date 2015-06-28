<?php
/**
 * Club Hg-Product
 *
 * Contests repository
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ContestsBundle\Repository\Contests
 */
class Contests extends EntityRepository
{

    const STATUS_ACTIVE  = 1;
    const STATUS_HIDDEN  = 2;


    /**
     * @return array
     */
    public function getIdsAllActualContests()
    {
        $currentDate = new \DateTime();
        $currentDate->setTime(0, 0, 0);

        $qb = $this->createQueryBuilder('c');
        $qb
            ->select(array('c.id'))
            ->where('c.status = :status')
            ->andWhere('c.startedAt <= :current_date')
            ->andWhere('c.finishedAt >= :current_date')
            ->setParameters(array(
                'status' => self::STATUS_ACTIVE,
                'current_date' => $currentDate,
            ))
        ;

        return $qb->getQuery()->getResult();
    }

}
