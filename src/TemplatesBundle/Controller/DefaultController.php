<?php
/**
 * Club Hg-Product
 *
 * Default controller
 *
 * @package    TemplatesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * TemplatesBundle\Controller\DefaultController
 */
class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('TemplatesBundle:Default:index.html.twig', array());
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function mainSliderAction()
    {
        $defaultSliders = array(
            1 => array(),
            2 => array(),
            3 => array(),
        );

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $mainSliderRepository = $em->getRepository('TemplatesBundle:MainSlider');
        $mainSlider = $mainSliderRepository->findAll();

        if ($mainSlider) {
            /** @var $slide \TemplatesBundle\Entity\MainSlider */
            foreach ($mainSlider as $slide) {
                $defaultSliders[$slide->getNumSlide()] = $slide;
            }
        }

        return array(
            'slides' => $defaultSliders,
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function smallSliderAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $contestsRepository \Application\ContestsBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationContestsBundle:Contests');
        $sliderContests = $contestsRepository->getActualContestsForSmallSlider();

        return array('sliderContests' => $sliderContests);
    }

}
