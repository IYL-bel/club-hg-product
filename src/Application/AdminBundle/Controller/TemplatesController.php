<?php
/**
 * Club Hg-Product
 *
 * Template controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

use TemplatesBundle\Form\Type\AddMainSlider;
use TemplatesBundle\Entity\MainSlider;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\TemplateController
 */
class TemplatesController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array
     */
    public function changeMainSliderAction(Request $request, $id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $mainSliderRepository = $em->getRepository('TemplatesBundle:MainSlider');
        $mainSlider = $mainSliderRepository->findOneBy(array('numSlide' => $id));

        if (!$mainSlider) {
            $mainSlider = new MainSlider();
            $mainSlider->setNumSlide($id);
        }

        $form = $this->createForm(new AddMainSlider(), $mainSlider);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mainSlider = $form->getData();
            $em->persist($mainSlider);
            $em->flush();

            return $this->redirectToRoute('application_admin_templates');
        }

        return array(
            'id' => $id,
            'form' => $form->createView(),
        );
    }

}
