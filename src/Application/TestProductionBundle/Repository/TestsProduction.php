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

    const STATUS__NEW           = 1;
    const STATUS__PRODUCT_SENT  = 2;

}
