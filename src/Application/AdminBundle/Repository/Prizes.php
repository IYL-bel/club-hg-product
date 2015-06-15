<?php
/**
 * Club Hg-Product
 *
 * Prizes repository
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\AdminBundle\Repository\Prizes
 */
class Prizes extends EntityRepository
{
    const TYPE_BRONZE     = 1;
    const TYPE_SILVER     = 2;
    const TYPE_GOLD       = 3;
    const TYPE_PLATINUM   = 4;


    const STATUS_ACTIVE  = 1;
    const STATUS_HIDDEN  = 2;


    /**
     * @static
     * @param string $preName
     * @return array
     */
    static public function getNamesType($preName = '')
    {
        return array(
            self::TYPE_BRONZE    => $preName . 'bronze',
            self::TYPE_SILVER    => $preName . 'silver',
            self::TYPE_GOLD      => $preName . 'gold',
            self::TYPE_PLATINUM  => $preName . 'platinum',
        );
    }



}
