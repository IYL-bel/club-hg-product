<?php
/**
 * Club Hg-Product
 *
 * Users repository
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\UsersBundle\Repository\Users
 */
class Users extends EntityRepository
{

    const TYPE_USER_FB   = 1;
    const TYPE_USER_VK   = 2;
    const TYPE_USER_OK   = 3;

}
