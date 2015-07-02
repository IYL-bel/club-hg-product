<?php
/**
 * Club Hg-Product
 *
 * Random Selection service
 *
 * @package    ApplicationBaseBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\BaseBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Application\BaseBundle\Service\RandomSelection
 */
class RandomSelection
{

    /**
     * @param $ids
     * @param $countResult
     * @return array|bool
     */
    public function chooseSeveralVariants($ids, $countResult)
    {
        if ($ids) {
            $allIds = array();
            foreach ($ids as $val) {
                $allIds[] = $val['id'];
            }
            shuffle($allIds);
            $randomIds = array_slice($allIds, 0, $countResult);

            return $randomIds;
        }

        return false;
    }

}
