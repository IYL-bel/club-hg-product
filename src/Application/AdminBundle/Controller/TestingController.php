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

        /** @var $shopProductionsManager \HgProductBundle\Manager\ShopProductions */
        $shopProductionsManager = $this->get('hg_product.shop_productions_manager');
        $testsProduction = $shopProductionsManager->addShopProductData($testsProduction);

        return array(
            'testsProduction' => $testsProduction
        );
    }

    /**
     * @Template()
     *
     * @param int $id
     * @return array
     */
    public function customerInfoMoreAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');
        /** @var $itemTestsProduction \Application\TestProductionBundle\Entity\TestsProduction */
        $testProduction = $testsProductionRepository->find($id);

        if (!$testProduction) {
            $this->redirectToRoute('application_admin_testing');
        }

        return array(
            'itemTestsProduction' => $testProduction,
        );
    }

}
