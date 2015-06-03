<?php
/**
 * Test task
 *
 * Users repository
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Test\UserBundle\Repository\UsersRepository
 */
class UsersRepository extends EntityRepository
{

    /**
     * @param \DateTime $dataBirth
     * @return array
     */
    public function getUsersTodayBirthday($dataBirth)
    {
        $query = $this->createQueryBuilder('u');
        // Variant 1
        /*
        $query
            ->where( $query->expr()->like('u.birthDate', ':date_birth') )
            ->setParameters( array('date_birth' => '%' . $dataBirth->format('m-d H:i:s')) )
            //->setParameters( array('date_birth' => '%' . $dataBirth->format('m-d') . '%') )
        ;*/

        // Variant 2
        $query
            ->where('MONTH(u.birthDate) = :month_birth')
            ->andWhere('DAY(u.birthDate) = :day_birth')
            ->setParameters(array(
                'day_birth' => $dataBirth->format('d'),
                'month_birth' => $dataBirth->format('m'),
            ))
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getAllUsers()
    {
        $query = $this->createQueryBuilder('u');
        $query
            ->join('u.gender', 'g')
        ;

        return $query->getQuery()->getResult();
    }

}
