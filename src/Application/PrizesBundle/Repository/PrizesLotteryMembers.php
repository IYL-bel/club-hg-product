<?php
/**
 * Club Hg-Product
 *
 * Prizes Lottery Members repository
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

use Application\PrizesBundle\Repository\PrizesLottery as PrizesLotteryRepository;


/**
 *  Application\PrizesBundle\Repository\PrizesLotteryMembers
 */
class PrizesLotteryMembers extends EntityRepository
{

    /**
     * @param int $prizeId
     * @return array
     */
    public function getMembersForCurrentPrize($prizeId)
    {
        $qb = $this->createQueryBuilder('plm');
        $qb
            ->join('plm.prizeLottery', 'pl')
            ->join('pl.prize', 'p')
            ->where('p.id = :prize_id')
            ->andWhere('pl.status = :status')
            ->setParameters(array(
                'status' => PrizesLotteryRepository::STATUS_ACTIVE,
                'prize_id' => $prizeId,
            ))
        ;

        return $qb->getQuery()->getResult();
    }

}
