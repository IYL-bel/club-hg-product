<?php
/**
 * Club Hg-Product
 *
 * Statuses controller
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

use TemplatesBundle\Repository\Statuses as StatusesRepository;
use TemplatesBundle\Form\Type\EditStatus as EditStatusForm;
use TemplatesBundle\Entity\Statuses;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\StatusesController
 */
class StatusesController extends Controller
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
        $statusesRepository = $em->getRepository('TemplatesBundle:Statuses');
        $statuses = $statusesRepository->findAll();

        $defaultStatuses = StatusesRepository::getAllStatuses();

        if ($statuses) {
            /** @var $status \TemplatesBundle\Entity\Statuses */
            foreach ($statuses as $status) {
                $defaultStatuses[$status->getNameStatus()] = $status;
            }
        }

        return array(
            'statuses' => $defaultStatuses,
            'default_scores' => StatusesRepository::getDefaultScoresForStatuses(),
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $name
     * @return array
     */
    public function editStatusAction(Request $request, $name)
    {
        $defaultStatuses = StatusesRepository::getAllStatuses();
        $allNameStatuses = array_keys($defaultStatuses);
        if ( !in_array($name, $allNameStatuses) ) {
            return $this->redirectToRoute('application_admin_statuses');
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $statusesRepository \TemplatesBundle\Repository\Statuses */
        $statusesRepository = $em->getRepository('TemplatesBundle:Statuses');

        $status = $statusesRepository->findOneBy(array('nameStatus' => $name));

        if (!$status) {
            /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
            $translator = $this->get('translator');

            $status = new Statuses();
            $status->setNameStatus($name);
            $status->setDescription( $translator->trans('templates.description.statuses.' . $name) );
            $status->setScores( $statusesRepository::getDefaultScoresForStatuses($name) );
        }

        $form = $this->createForm(new EditStatusForm(), $status);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $status = $form->getData();

            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('application_admin_statuses');
        }

        return array(
            'name' => $name,
            'form' => $form->createView(),
        );
    }

}
