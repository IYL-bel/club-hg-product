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

}
