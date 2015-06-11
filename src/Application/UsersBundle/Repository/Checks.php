<?php
/**
 * Club Hg-Product
 *
 * Checks repository
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\UsersBundle\Repository\Checks
 */
class Checks extends EntityRepository
{

    const STATUS_NEW        = 1;
    const STATUS_CONFIRMED  = 2;
    const STATUS_REJECTED   = 3;

}
