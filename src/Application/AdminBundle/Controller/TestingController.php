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

use Application\AdminBundle\Form\Type\TestingProductionDisallow as TestingProductionDisallowForm;


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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approvedRequestTestAction(Request $request, $id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');

        /** @var $testProduction \Application\TestProductionBundle\Entity\TestsProduction */
        $testProduction = $testsProductionRepository->findOneBy( array(
            'id' => $id,
            'status' => $testsProductionRepository::STATUS_NEW
        ));

        if ($testProduction) {
            $testProduction->setStatus($testsProductionRepository::STATUS_CONFIRMED);
            $testProduction->setProcessingAt( new \DateTime('now') );

            $em->persist($testProduction);
            $em->flush();

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->additionUserScore( $testProduction->getScore(), $testProduction->getUser() );
        }

        return $this->redirectToRoute('application_admin_testing');
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disallowRequestTestAction(Request $request, $id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');

        /** @var $testProduction \Application\TestProductionBundle\Entity\TestsProduction */
        $testProduction = $testsProductionRepository->findOneBy( array(
            'id' => $id,
            'status' => $testsProductionRepository::STATUS_NEW
        ));

        if (!$testProduction) {
            $this->redirectToRoute('application_admin_testing');
        }

        $form = $this->createForm(new TestingProductionDisallowForm(), $testProduction );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $testProduction = $form->getData();

            $testProduction->setStatus($testsProductionRepository::STATUS_REJECTED);
            $testProduction->setProcessingAt( new \DateTime('now') );

            $em->persist($testProduction);
            $em->flush();

            return $this->redirectToRoute('application_admin_testing');
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
