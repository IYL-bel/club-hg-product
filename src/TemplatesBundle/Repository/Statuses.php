<?php
/**
 * Club Hg-Product
 *
 * Statuses repository
 *
 * @package    TemplatesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * TemplatesBundle\Repository\Statuses
 */
class Statuses extends EntityRepository
{

    /**
     * @static
     * @return array
     */
    static public function getAllStatuses()
    {
        return array(
            'bronze' => array(),
            'silver' => array(),
            'gold' => array(),
            'platinum' => array(),
        );
    }

    /**
     * @static
     * @param null|string $name
     * @return array|string
     */
    static public function getDefaultScoresForStatuses($name = null)
    {
        $scores = array(
            'bronze' => 100,
            'silver' => 300,
            'gold' => 500,
            'platinum' => 1000,
        );

        if ($name) {
            $allNameStatuses = array_keys($scores);
            if ( in_array($name, $allNameStatuses) ) {
                return $scores[$name];
            }
        }

        return $scores;
    }

}
