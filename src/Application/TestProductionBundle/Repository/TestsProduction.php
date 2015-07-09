<?php
/**
 * Club Hg-Product
 *
 * Tests Production repository
 *
 * @package    ApplicationTestProductionBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\TestProductionBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Application\TestProductionBundle\Repository\TestsProduction
 */
class TestsProduction extends EntityRepository
{

    const STATUS_NEW        = 1;
    const STATUS_CONFIRMED  = 2;
    const STATUS_REJECTED   = 3;

}
