<?php
/**
 * Club Hg-Product
 *
 * Scores repository
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ScoresBundle\Repository\Scores
 */
class Scores extends EntityRepository
{

    const TYPE__CONTESTS_PARTICIPATION   = 1;
    const TYPE__CONTESTS_WINNER          = 2;
    const TYPE__PRIZES                   = 20;

}
