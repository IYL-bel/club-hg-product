<?php
/**
 * Club Hg-Product
 *
 * Contests controller
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

use Application\AdminBundle\Form\Type\CheckDisallow as CheckDisallowForm;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
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
        $checks = $checksRepository->findBy( array(), array('createdAt' => 'DESC') );

        return array(
            'checks' => $checks,
        );
    }

    /**
     * @Template()
     *
     * @param int $id
     * @return array
     */
    public function approvedCheckAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $checksRepository \Application\UsersBundle\Repository\Checks */
        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        /** @var $check \Application\UsersBundle\Entity\Checks */
        $check = $checksRepository->findOneBy(array(
            'id' => $id,
            'status' => $checksRepository::STATUS_NEW
        ));

        if ($check) {
            $check->setStatus($checksRepository::STATUS_CONFIRMED);
            $check->setProcessingAt( new \DateTime('now') );

            $em->persist($check);
            $em->flush();
        }

        return $this->redirectToRoute('application_admin_checks');
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array
     */
    public function disallowCheckAction(Request $request, $id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $checksRepository \Application\UsersBundle\Repository\Checks */
        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        /** @var $check \Application\UsersBundle\Entity\Checks */
        $check = $checksRepository->findOneBy(array(
            'id' => $id,
            'status' => $checksRepository::STATUS_NEW
        ));

        if (!$check) {
            return $this->redirectToRoute('application_admin_checks');
        }

        $form = $this->createForm(new CheckDisallowForm(), $check);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $check = $form->getData();
            $check->setStatus($checksRepository::STATUS_REJECTED);
            $check->setProcessingAt( new \DateTime('now') );

            $em->persist($check);
            $em->flush();

            return $this->redirectToRoute('application_admin_checks');
        }

        return array( 'form' => $form->createView() );
    }

}
