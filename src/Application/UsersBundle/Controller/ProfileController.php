<?php
/**
 * Club Hg-Product
 *
 * Profile controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Application\UsersBundle\Controller\ProfileController
 */
class ProfileController extends Controller
{

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function myAction()
    {
        $profStatuses[] = array(
            'title' => 'БРОНЗА',
            'name_medal' => 'bronze',
            'comments' => array(
                'Бесплатно тестировать средства HG раз в месяц',
                'Участвовать в "бронзовых" розыгрышах',
                'Ообменивать баллы на "бронзовые" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'СЕРЕБРО',
            'name_medal' => 'silver',
            'comments' => array(
               'Бесплатно тестировать средства HG два в месяц',
                'Участвовать в "серебрянных" розыгрышах',
                'Обменивать баллы на "серебрянные" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'ЗОЛОТО',
            'name_medal' => 'gold',
            'comments' => array(
                'Бесплатно тестировать средства HG три в месяц',
                'Участвовать в "золотых" розыгрышах',
                'Обменивать баллы на "золотые" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'ПЛАТИНА',
            'name_medal' => 'platinum',
            'comments' => array(
                'Бесплатно тестировать любые средства HG, но не более одного средства один раз.',
                'Участвовать в "платиновых" розыгрышах',
                'Обменивать баллы на "платиновые" призы'
            )
        );






        return array('prof_statuses' => $profStatuses);
    }

}
