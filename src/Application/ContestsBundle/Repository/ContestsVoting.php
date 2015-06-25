<?php
/**
 * Club Hg-Product
 *
 * Contests Voting repository
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ContestsBundle\Repository\ContestsVoting
 */
class ContestsVoting extends EntityRepository
{

    const TYPE_FB  = 1;
    const TYPE_VK  = 2;
    const TYPE_OK  = 3;

}
