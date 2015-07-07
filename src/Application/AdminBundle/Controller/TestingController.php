<?php
/**
 * Club Hg-Product
 *
 * Testing controller
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

use Application\AdminBundle\Form\Type\CommentProductionDisallow as CommentProductionDisallowForm;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\TestingController
 */
class TestingController extends Controller
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
        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');
        $testsProduction = $testsProductionRepository->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'testsProduction' => $testsProduction
        );
    }

}
