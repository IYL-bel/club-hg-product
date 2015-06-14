<?php
/**
 * Club Hg-Product
 *
 * Contests controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Application\AdminBundle\Controller\ChecksController
 */
class ChecksController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        $checks = $checksRepository->findAll();

        return array(
            'checks' => $checks,
        );
    }

}
