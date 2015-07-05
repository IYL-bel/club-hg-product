<?php
/**
 * Club Hg-Product
 *
 * Contests Voting repository
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ContestsBundle\Repository\ContestsVoting
 */
class ContestsVoting extends EntityRepository
{

    const TYPE_FB  = 1;
    const TYPE_VK  = 2;
    const TYPE_OK  = 3;


    /**
     * @param string $type
     * @param int $contestsMemberId
     * @return array
     */
    public function getCountVoteForType($type, $contestsMemberId)
    {
        $qb = $this->createQueryBuilder('cv');
        $qb
            ->select( $qb->expr()->count('cv.id')  )
            ->join('cv.contestsMember', 'cm')
            ->where('cv.type = :type')
            ->andWhere('cm.id = :memberId')
            ->setParameters(array(
                'type' => $type,
                'memberId' => $contestsMemberId
            ))
        ;

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

}
