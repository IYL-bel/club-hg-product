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
    const STATUS_ACTIVE  = 1;
    const STATUS_HIDDEN  = 2;

}
