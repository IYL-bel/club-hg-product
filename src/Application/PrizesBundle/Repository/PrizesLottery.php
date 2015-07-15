<?php
/**
 * Club Hg-Product
 *
 * Prizes Lottery repository
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\PrizesBundle\Repository\PrizesLottery
 */
class PrizesLottery extends EntityRepository
{

    const STATUS_ACTIVE  = 1;
    const STATUS_FINISHED  = 2;

}
