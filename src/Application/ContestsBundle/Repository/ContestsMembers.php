<?php
/**
 * Club Hg-Product
 *
 * Contests Members repository
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ContestsBundle\Repository\ContestsMembers
 */
class ContestsMembers extends EntityRepository
{

    const STATUS_NEW        = 1;
    const STATUS_CONFIRMED  = 2;
    const STATUS_REJECTED   = 3;


    /**
     * @param int $contestId
     * @return array
     */
    public function getConfirmedMembersForSelectContest($contestId)
    {
        $qb = $this->createQueryBuilder('cm');
        $qb
            ->join('cm.contest', 'c')
            ->where('c.id = :contest_id')
            ->andWhere('cm.status = :status')
            ->orderBy('c.updatedAt', 'DESC')
            //->setMaxResults(10)
            ->setParameters(array(
                'contest_id' => $contestId,
                'status' => self::STATUS_CONFIRMED,
            ))
        ;

        return $qb->getQuery()->getResult();
    }

    public function getMembersSortMaxVoting($id)
    {
        $qb = $this->createQueryBuilder('cm');
        $qb
            ->select(array('cm', 'COUNT(cm.id) as cnt')   )
            ->where('cm.contest = :id_contest')
            ->orderBy('cm.updatedAt', 'DESC')
            ->setParameters(array(
                'id_contest' => $id,
            ))
        ;

        return $qb->getQuery()->getResult();
    }

}
