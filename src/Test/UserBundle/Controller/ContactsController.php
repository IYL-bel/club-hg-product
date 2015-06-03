<?php
/**
 * Test task
 *
 * Default controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Test\UserBundle\Controller\DefaultController
 */
class ContactsController extends Controller
{

    /**
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allUsersAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getEntityManager();
        $usersRepository = $em->getRepository('TestUserBundle:Users');
        $users = $usersRepository->findAll();

        return array('users' => $users);
    }

}
