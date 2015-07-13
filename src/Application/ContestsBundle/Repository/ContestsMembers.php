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
     * @param \Application\ContestsBundle\Entity\Contests $contest
     * @return array
     */
    public function getCountConfirmedMembersForSelectContest($contest)
    {
        $qb = $this->createQueryBuilder('cm');
        $qb
            ->select( $qb->expr()->count('cm.id') )
            ->join('cm.contest', 'c')
            ->where('c.id = :contest_id')
            ->andWhere('cm.status = :status')
            ->setParameters(array(
                'contest_id' => $contest->getId(),
                'status' => self::STATUS_CONFIRMED,
            ))
        ;

        if ( $contest->getMemberWinner() ) {
            $qb->andWhere( $qb->expr()->notIn('cm.id', array( $contest->getMemberWinner()->getId() )) );
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param \Application\ContestsBundle\Entity\Contests $contest
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getConfirmedMembersForSelectContest($contest, $limit, $offset)
    {
        $qb = $this->createQueryBuilder('cm');
        $qb
            ->join('cm.contest', 'c')
            ->where('c.id = :contest_id')
            ->andWhere('cm.status = :status')
            ->orderBy('c.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameters(array(
                'contest_id' => $contest->getId(),
                'status' => self::STATUS_CONFIRMED,
            ))
        ;

        if ( $contest->getMemberWinner() ) {
            $qb->andWhere( $qb->expr()->notIn('cm.id', array( $contest->getMemberWinner()->getId() )) );
        }

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
