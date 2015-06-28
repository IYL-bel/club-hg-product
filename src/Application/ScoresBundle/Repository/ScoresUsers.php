<?php
/**
 * Club Hg-Product
 *
 * Scores Users repository
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ScoresBundle\Repository\ScoresUsers
 */
class ScoresUsers extends EntityRepository
{

    const TYPE_CALCULATION__ADDITION     = 1;
    const TYPE_CALCULATION__SUBTRACTION  = 2;


    /**
     * @param int $userId
     * @param string $scoreType
     * @return array
     */
    public function getSharingUserFromTime($userId, $scoreType)
    {
        $lastWeekDate = new \DateTime();
        $lastWeekDate->modify('-7 day');

        $qb = $this->createQueryBuilder('su');
        $qb
            ->select( array('su.id', 'su.createdAt') )
            ->join('su.score', 's')
            ->join('su.user', 'u')
            ->where('u.id = :user_id')
            ->andWhere('s.type = :score_type')
            ->orderBy('su.createdAt', 'DESC')
            ->setMaxResults(1)
            ->setParameters(array(
                'user_id' => $userId,
                'score_type' => $scoreType,
            ))
        ;

        return $qb->getQuery()->getResult();
    }

}
